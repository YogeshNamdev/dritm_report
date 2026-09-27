<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel library (CodeIgniter 3)
 * ------------------------------------------------------------------
 * Lightweight .xlsx / .csv reader — no Composer / PhpSpreadsheet needed.
 * Uses ZipArchive + SimpleXML (both are standard PHP extensions).
 *
 * Usage:
 *   $this->load->library('excel');
 *   $rows = $this->excel->read_sheet('/path/to/file.xlsx', 'Raw Report');
 *   // $rows = array of associative arrays, keyed by header-row values
 *
 * If $sheet_name is not found (or null), the first sheet is used.
 * For .csv files, $sheet_name is ignored.
 */
class Excel {

    private $shared_strings = array();
    private $date_styles = array();   // style index => 'date' | 'time' | 'datetime'

    public function read_sheet($file_path, $sheet_name = null)
    {
        if(!file_exists($file_path))
        {
            throw new Exception("File not found: {$file_path}");
        }

        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

        if($ext == 'csv')
        {
            return $this->read_csv($file_path);
        }

        if($ext == 'xlsx')
        {
            return $this->read_xlsx($file_path, $sheet_name);
        }

        if($ext == 'xls')
        {
            throw new Exception("Old .xls format supported nahi hai — file ko .xlsx ya .csv me save karke upload karo.");
        }

        throw new Exception("Unsupported file type: {$ext}");
    }

    // ------------------------------------------------------------------
    // CSV
    // ------------------------------------------------------------------

    private function read_csv($file_path)
    {
        $rows = array();
        $handle = fopen($file_path, 'r');
        if($handle === FALSE)
        {
            throw new Exception("CSV file open nahi hui.");
        }

        // BOM strip
        $first_bytes = fread($handle, 3);
        if($first_bytes !== chr(0xEF).chr(0xBB).chr(0xBF))
        {
            rewind($handle);
        }

        $header = fgetcsv($handle);
        if($header === FALSE)
        {
            fclose($handle);
            return $rows;
        }
        $header = array_map('trim', $header);

        while(($line = fgetcsv($handle)) !== FALSE)
        {
            if(count($line) == 1 && trim($line[0]) === '') continue; // skip blank lines
            $row = array();
            foreach($header as $idx => $col_name)
            {
                $row[$col_name] = isset($line[$idx]) ? trim($line[$idx]) : '';
            }
            $rows[] = $row;
        }
        fclose($handle);
        return $rows;
    }

    // ------------------------------------------------------------------
    // XLSX
    // ------------------------------------------------------------------

    private function read_xlsx($file_path, $sheet_name)
    {
        $zip = new ZipArchive();
        if($zip->open($file_path) !== TRUE)
        {
            throw new Exception("XLSX file open nahi hui (corrupt ya invalid).");
        }

        // 1. shared strings
        $this->shared_strings = $this->load_shared_strings($zip);

        // 2. styles -> date/time format detection
        $this->date_styles = $this->load_date_styles($zip);

        // 3. sheet name -> target xml path
        $sheet_target = $this->resolve_sheet_target($zip, $sheet_name);
        if($sheet_target === FALSE)
        {
            $zip->close();
            throw new Exception("Sheet '{$sheet_name}' file me nahi mili.");
        }

        // 4. parse sheet rows
        $sheet_xml = $zip->getFromName('xl/'.$sheet_target);
        $zip->close();

        if($sheet_xml === FALSE)
        {
            throw new Exception("Sheet data read nahi hui.");
        }

        return $this->parse_sheet_xml($sheet_xml);
    }

    private function load_shared_strings($zip)
    {
        $strings = array();
        $xml_data = $zip->getFromName('xl/sharedStrings.xml');
        if($xml_data === FALSE) return $strings;

        $xml = simplexml_load_string($xml_data);
        if($xml === FALSE) return $strings;

        foreach($xml->si as $si)
        {
            if(isset($si->t))
            {
                $strings[] = (string)$si->t;
            }
            else
            {
                // rich text runs <r><t>...</t></r>
                $text = '';
                foreach($si->r as $r)
                {
                    $text .= (string)$r->t;
                }
                $strings[] = $text;
            }
        }
        return $strings;
    }

    private function load_date_styles($zip)
    {
        $date_styles = array();
        $xml_data = $zip->getFromName('xl/styles.xml');
        if($xml_data === FALSE) return $date_styles;

        $xml = simplexml_load_string($xml_data);
        if($xml === FALSE) return $date_styles;

        // custom numFmts (id >= 164 usually)
        $custom_formats = array();
        if(isset($xml->numFmts))
        {
            foreach($xml->numFmts->numFmt as $nf)
            {
                $id = (int)$nf['numFmtId'];
                $code = (string)$nf['formatCode'];
                $custom_formats[$id] = $code;
            }
        }

        // built-in date/time numFmtIds (per OOXML spec)
        $builtin_date_ids = array(14,15,16,17,22,27,28,29,30,31,32,33,34,35,36,45,46,47,50,51,52,53,54,55,56,57,58);
        $builtin_time_only_ids = array(18,19,20,21,45,46,47); // h:mm, h:mm:ss variants

        if(isset($xml->cellXfs))
        {
            $index = 0;
            foreach($xml->cellXfs->xf as $xf)
            {
                $numFmtId = isset($xf['numFmtId']) ? (int)$xf['numFmtId'] : 0;
                $type = null;

                if($numFmtId > 0)
                {
                    $code = isset($custom_formats[$numFmtId]) ? $custom_formats[$numFmtId] : null;

                    if(in_array($numFmtId, $builtin_time_only_ids))
                    {
                        $type = 'time';
                    }
                    else if(in_array($numFmtId, $builtin_date_ids))
                    {
                        $type = 'date';
                    }
                    else if($code !== null)
                    {
                        // Sirf 'd' / 'y' ko date-indicator maante hain aur 'h' / 's' ko time-indicator —
                        // 'm' ko jaan-boojh kar ignore kiya hai kyunki wo month(date) aur minute(time)
                        // dono ho sakta hai; ambiguity, case-sensitive match se AM/PM literal bhi safe rehta hai.
                        $has_date_chars = (bool)preg_match('/[dy]/', $code);
                        $has_time_chars = (bool)preg_match('/[hs]/', $code);
                        if($has_date_chars && $has_time_chars) $type = 'datetime';
                        else if($has_date_chars) $type = 'date';
                        else if($has_time_chars) $type = 'time';
                    }
                }

                if($type !== null)
                {
                    $date_styles[$index] = $type;
                }
                $index++;
            }
        }

        return $date_styles;
    }

    private function resolve_sheet_target($zip, $sheet_name)
    {
        $workbook_xml = $zip->getFromName('xl/workbook.xml');
        $rels_xml = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if($workbook_xml === FALSE || $rels_xml === FALSE) return FALSE;

        $workbook = simplexml_load_string($workbook_xml);
        $rels = simplexml_load_string($rels_xml);

        // rId -> target path
        $rid_map = array();
        foreach($rels->Relationship as $rel)
        {
            $rid_map[(string)$rel['Id']] = (string)$rel['Target'];
        }

        $ns = $workbook->getNamespaces(true);
        $r_ns = isset($ns['r']) ? $ns['r'] : 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

        $found_target = FALSE;
        $first_target = FALSE;

        foreach($workbook->sheets->sheet as $sheet)
        {
            $attrs_r = $sheet->attributes($r_ns);
            $rid = (string)$attrs_r['id'];
            $target = isset($rid_map[$rid]) ? $rid_map[$rid] : FALSE;
            if($target === FALSE) continue;

            // normalize target path (usually "worksheets/sheetN.xml")
            $target = ltrim($target, '/');

            if($first_target === FALSE) $first_target = $target;

            if($sheet_name !== null && (string)$sheet['name'] === $sheet_name)
            {
                $found_target = $target;
                break;
            }
        }

        if($found_target !== FALSE) return $found_target;
        if($sheet_name === null) return $first_target;

        // sheet_name diya tha par exact match nahi mila -> first sheet pe fallback mat karo, FALSE return karo
        return FALSE;
    }

    private function parse_sheet_xml($sheet_xml)
    {
        $xml = simplexml_load_string($sheet_xml);
        if($xml === FALSE)
        {
            throw new Exception("Sheet XML parse nahi hua.");
        }

        $raw_rows = array(); // row_index => [col_letter => value]

        foreach($xml->sheetData->row as $row)
        {
            $row_num = (int)$row['r'];
            $row_data = array();

            foreach($row->c as $cell)
            {
                $ref = (string)$cell['r'];
                $col_letter = preg_replace('/[0-9]/', '', $ref);
                $type = isset($cell['t']) ? (string)$cell['t'] : null;
                $style_idx = isset($cell['s']) ? (int)$cell['s'] : null;

                $value = $this->extract_cell_value($cell, $type, $style_idx);
                $row_data[$col_letter] = $value;
            }

            $raw_rows[$row_num] = $row_data;
        }

        if(empty($raw_rows)) return array();

        $row_numbers = array_keys($raw_rows);
        sort($row_numbers);

        $header_row_num = $row_numbers[0];
        $header_row = $raw_rows[$header_row_num];

        // col_letter => header name
        $headers = array();
        foreach($header_row as $col_letter => $val)
        {
            $headers[$col_letter] = trim((string)$val);
        }

        $result = array();
        for($i = 1; $i < count($row_numbers); $i++)
        {
            $r_num = $row_numbers[$i];
            $data_row = $raw_rows[$r_num];

            $assoc = array();
            $all_blank = TRUE;
            foreach($headers as $col_letter => $header_name)
            {
                if($header_name === '') continue;
                $val = isset($data_row[$col_letter]) ? $data_row[$col_letter] : '';
                if($val !== '') $all_blank = FALSE;
                $assoc[$header_name] = $val;
            }

            if($all_blank) continue; // poori row khali hai to skip

            $result[] = $assoc;
        }

        return $result;
    }

    private function extract_cell_value($cell, $type, $style_idx)
    {
        if($type == 's')
        {
            // shared string index
            $idx = (int)$cell->v;
            return isset($this->shared_strings[$idx]) ? $this->shared_strings[$idx] : '';
        }

        if($type == 'inlineStr')
        {
            if(isset($cell->is->t)) return (string)$cell->is->t;
            $text = '';
            if(isset($cell->is->r))
            {
                foreach($cell->is->r as $r) $text .= (string)$r->t;
            }
            return $text;
        }

        if($type == 'b')
        {
            return ((string)$cell->v == '1') ? 'TRUE' : 'FALSE';
        }

        if($type == 'str')
        {
            // formula result as string
            return (string)$cell->v;
        }

        // numeric (default, no 't' attribute) — check if it's a date/time style
        $raw_value = isset($cell->v) ? (string)$cell->v : '';
        if($raw_value === '') return '';

        if($style_idx !== null && isset($this->date_styles[$style_idx]))
        {
            return $this->excel_serial_to_string((float)$raw_value, $this->date_styles[$style_idx]);
        }

        return $raw_value;
    }

    /**
     * Excel serial date (days since 1899-12-30) -> formatted string
     */
    private function excel_serial_to_string($serial, $type)
    {
        // SANITY GUARD: Excel date serials always start at 1 (= 1899-12-31).
        // Kuch converted files (jaise LibreOffice round-trip) me galat style
        // assign ho jaati hai — agar value < 1 hai to wo kabhi bhi ek real
        // date nahi ho sakti, sirf ek time-of-day fraction ho sakti hai.
        // Isliye style jo bhi kahe, is case me hum time hi treat karenge.
        if($serial < 1 && $type != 'time')
        {
            $type = 'time';
        }

        // Excel's leap-year bug (1900 treated as leap year) is baked into this base date
        $base_ts = -2209161600; // 1899-12-30 00:00:00 UTC
        $seconds = round($serial * 86400);
        $ts = $base_ts + $seconds;

        if($type == 'time')
        {
            return gmdate('H:i:s', $ts);
        }
        if($type == 'datetime')
        {
            return gmdate('Y-m-d H:i:s', $ts);
        }
        return gmdate('Y-m-d', $ts); // 'date'
    }

}   