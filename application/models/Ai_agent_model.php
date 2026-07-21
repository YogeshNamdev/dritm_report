<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ai_agent_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->config->load('ai_agent', TRUE);
    }

    public function enhance_remark($remark)
    {
        $remark = trim((string)$remark);
        if($remark === '')
        {
            return $this->failure('Remark is required.');
        }

        if($this->setting('ai_agent_provider', 'huggingface_api') === 'direct_groq')
        {
            return $this->call_groq($remark);
        }

        $hf = $this->call_huggingface_api($remark);
        if($hf['success'])
        {
            return $hf;
        }

        if($this->setting('ai_agent_allow_direct_groq_fallback', FALSE))
        {
            $groq = $this->call_groq($remark);
            if($groq['success'])
            {
                $groq['provider'] = 'groq_fallback';
                return $groq;
            }
        }

        return $hf;
    }

    private function call_huggingface_api($remark)
    {
        $url = trim($this->setting('ai_agent_huggingface_api_url', ''));
        if($url === '')
        {
            return $this->failure('Hugging Face API URL is not configured.');
        }

        $headers = array('Content-Type: application/json', 'Accept: application/json');
        $token = trim($this->setting('ai_agent_huggingface_token', ''));
        if($token !== '')
        {
            $headers[] = 'Authorization: Bearer '.$token;
        }

        $response = $this->http_post_json($url, array('remark' => $remark), $headers);
        if(!$response['success'])
        {
            return $response;
        }

        $content_type = isset($response['headers']['content_type']) ? $response['headers']['content_type'] : '';
        $body = trim($response['body']);

        if(stripos($content_type, 'text/html') !== FALSE || stripos($body, '<!DOCTYPE html') === 0 || stripos($body, '<html') !== FALSE)
        {
            return $this->failure('Hugging Face returned the Streamlit HTML page instead of JSON. Deploy the /api/enhance FastAPI endpoint included with this implementation.', $response['http_code'], $this->short_body($body));
        }

        $decoded = json_decode($body, TRUE);
        if(!is_array($decoded))
        {
            return $this->failure('Invalid JSON received from Hugging Face API.', $response['http_code'], $this->short_body($body));
        }

        if(isset($decoded['detail']))
        {
            return $this->failure(is_string($decoded['detail']) ? $decoded['detail'] : 'Hugging Face API returned an error.', $response['http_code'], $this->short_body($body));
        }

        $text = $this->extract_text($decoded);
        if($text === '')
        {
            return $this->failure('Hugging Face API did not return an enhanced remark.', $response['http_code'], $this->short_body($body));
        }

        return $this->success($text, 'huggingface_api', $response['http_code']);
    }

    private function call_groq($remark)
    {
        $api_key = trim($this->setting('ai_agent_groq_api_key', ''));
        if($api_key === '')
        {
            return $this->failure('GROQ_API_KEY is not configured on the CRM server.');
        }

        $payload = array(
            'model' => $this->setting('ai_agent_groq_model', 'llama-3.1-8b-instant'),
            'messages' => array(array('role' => 'user', 'content' => $this->build_prompt($remark))),
            'temperature' => 0.3
        );

        $response = $this->http_post_json(
            $this->setting('ai_agent_groq_api_url', ''),
            $payload,
            array('Content-Type: application/json', 'Accept: application/json', 'Authorization: Bearer '.$api_key)
        );

        if(!$response['success'])
        {
            return $response;
        }

        $decoded = json_decode(trim($response['body']), TRUE);
        if(!is_array($decoded))
        {
            return $this->failure('Invalid JSON received from Groq API.', $response['http_code'], $this->short_body($response['body']));
        }

        if(isset($decoded['error']['message']))
        {
            return $this->failure($decoded['error']['message'], $response['http_code'], $this->short_body($response['body']));
        }

        $text = $this->extract_text($decoded);
        if($text === '')
        {
            return $this->failure('Groq API did not return an enhanced remark.', $response['http_code'], $this->short_body($response['body']));
        }

        return $this->success($text, 'groq', $response['http_code']);
    }

    private function http_post_json($url, $payload, $headers)
    {
        if(!function_exists('curl_init'))
        {
            return $this->failure('PHP cURL extension is not enabled.');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int)$this->setting('ai_agent_connect_timeout', 15));
        curl_setopt($ch, CURLOPT_TIMEOUT, (int)$this->setting('ai_agent_timeout', 60));
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

        $verify_ssl = (bool)$this->setting('ai_agent_ssl_verify', TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verify_ssl ? 2 : 0);

        $ca_bundle = trim($this->setting('ai_agent_ca_bundle', ''));
        if($ca_bundle !== '')
        {
            curl_setopt($ch, CURLOPT_CAINFO, $ca_bundle);
        }

        $raw = curl_exec($ch);
        if($raw === FALSE)
        {
            $error = curl_error($ch);
            curl_close($ch);
            return $this->failure('Network error while calling AI service: '.$error);
        }

        $http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = (int)curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $content_type = (string)curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        $body = substr($raw, $header_size);
        if($http_code < 200 || $http_code >= 300)
        {
            return $this->failure('AI service returned HTTP '.$http_code.'.', $http_code, $this->short_body($body));
        }

        return array('success' => TRUE, 'http_code' => $http_code, 'headers' => array('content_type' => $content_type), 'body' => $body);
    }

    private function extract_text($decoded)
    {
        $keys = array('enhanced_remark', 'remark', 'result', 'response', 'text');
        foreach($keys as $key)
        {
            if(isset($decoded[$key]) && is_string($decoded[$key]) && trim($decoded[$key]) !== '')
            {
                return trim($decoded[$key]);
            }
        }

        if(isset($decoded['choices'][0]['message']['content']))
        {
            return trim($decoded['choices'][0]['message']['content']);
        }

        if(isset($decoded['data'][0]) && is_string($decoded['data'][0]))
        {
            return trim($decoded['data'][0]);
        }

        return '';
    }

    private function build_prompt($remark)
    {
        $language_style = $this->detect_language_style($remark);

        return 'You are an AI Remark Enhancement Agent for a Government Complaint Management System.'."\n\n"
            .'Rewrite the given officer remark into a polished, professional, publication-ready official remark.'."\n\n"
            .'Detected input language/style: '.$language_style."\n\n"
            .'Language preservation rules:'."\n"
            .'- You must return the output in exactly this detected language/style: '.$language_style.'.'."\n"
            .'- If detected style is Hindi, return Hindi in Devanagari script only.'."\n"
            .'- If detected style is English, return English only.'."\n"
            .'- If detected style is Hinglish, return Hinglish in Latin/Roman script only.'."\n"
            .'- Do not translate the remark into another language unless the user explicitly asks for translation.'."\n"
            .'- Do not convert Hindi to English, English to Hindi, or Hinglish to Devanagari.'."\n\n"
            .'Content rules:'."\n"
            .'- Preserve the exact meaning and intent.'."\n"
            .'- Do not change any factual information.'."\n"
            .'- Do not add assumptions, new facts, or extra details.'."\n"
            .'- Correct grammar, spelling, punctuation, and sentence structure.'."\n"
            .'- Improve clarity, readability, flow, and official tone.'."\n"
            .'- Keep names, complaint numbers, dates, departments, locations, and technical terms unchanged.'."\n"
            .'- Return only the final enhanced remark.'."\n"
            .'- Do not write labels, markdown, explanations, introductions, confirmations, notes, translations, or phrases such as "Enhanced Remark" or "The remark has been rewritten".'."\n\n"
            .'Remark:'."\n".$remark;
    }

    private function detect_language_style($text)
    {
        if(preg_match('/[\x{0900}-\x{097F}]/u', $text))
        {
            return 'Hindi';
        }

        $hinglish_words = array(
            'hai', 'hain', 'ho', 'raha', 'rahi', 'rahe', 'hoga', 'hogi', 'kar', 'karo',
            'ki', 'ka', 'ke', 'ko', 'se', 'par', 'mein', 'me', 'aur', 'ya', 'nahi',
            'log', 'logon', 'janata', 'sadak', 'road', 'gaddha', 'pani', 'bijli',
            'samasya', 'dikkat', 'pareshani', 'shikayat', 'kripya', 'jaldi'
        );

        preg_match_all('/[A-Za-z]+/', strtolower($text), $matches);
        $hits = 0;
        foreach($matches[0] as $word)
        {
            if(in_array($word, $hinglish_words))
            {
                $hits++;
            }
        }

        return ($hits >= 2) ? 'Hinglish' : 'English';
    }

    private function setting($key, $default)
    {
        $value = $this->config->item($key, 'ai_agent');
        return ($value === NULL) ? $default : $value;
    }

    private function success($remark, $provider, $http_code)
    {
        return array('success' => TRUE, 'enhanced_remark' => trim($remark), 'provider' => $provider, 'http_code' => $http_code);
    }

    private function failure($message, $http_code = 0, $raw = '')
    {
        if(function_exists('log_message'))
        {
            log_message('error', 'AI remark enhancer failed: '.$message.($raw !== '' ? ' Raw: '.$raw : ''));
        }

        return array('success' => FALSE, 'message' => $message, 'http_code' => $http_code, 'raw_response' => $raw);
    }

    private function short_body($body)
    {
        $body = trim(strip_tags((string)$body));
        $body = preg_replace('/\s+/', ' ', $body);
        return substr($body, 0, 500);
    }

    public function insert_enhance_remark($data)
	{
		$this->db->insert("enhance_remark_request", $data);
		return $this->db->insert_id();
	}
}
