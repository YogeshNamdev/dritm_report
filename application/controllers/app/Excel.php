<?php
 
namespace App\Controllers;
 
use App\Models\TaskModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
class Excel extends BaseController
{
 public function index()
 {
     $taskModel = new TaskModel();
     $tasks = $taskModel->findAll();
 
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'Task');
        $sheet->setCellValue('C1', 'Status');
        $rows = 2;
 
        foreach ($tasks as $task){
            $sheet->setCellValue('A' . $rows, $task['id']);
            $sheet->setCellValue('B' . $rows, $task['task']);
            $sheet->setCellValue('C' . $rows, $task['status']);
            $rows++;
        }
 
        $writer = new Xlsx($spreadsheet);
        $writer->save('world.xlsx');
        return $this->response->download('world.xlsx', null)->setFileName('sample.xlsx');
        
    }
}
