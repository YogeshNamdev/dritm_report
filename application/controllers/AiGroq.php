<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AiGroq extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->model('Ai_agent_model');
        $this->config->load('ai_agent', TRUE);
    }

    public function index()
    {
        $data['enhanced_remark'] = '';
        $data['raw_remark'] = '';
        $data['error_message'] = '';

        if($this->input->post('submit'))
        {
            $raw_remark = $this->input->post('remark', TRUE);
            $data['raw_remark'] = $raw_remark;

            if(trim((string)$raw_remark) !== '')
            {
                $result = $this->Ai_agent_model->enhance_remark($raw_remark);
                if($result['success'])
                {
                    
                    $data['enhanced_remark'] = $result['enhanced_remark'];
                }
                else
                {
                    $data['error_message'] = $result['message'];
                }
            }
        }

        $this->load->view('ai_agent_view', $data);
    }

    public function enhance_remark()
    {
        $this->send_cors_headers();

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
        {
            return $this->json_response(array('response' => TRUE));
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            return $this->json_response(array('response' => FALSE, 'message' => 'Only POST requests are allowed.'), 405);
        }

        $remark = $this->posted_remark();
        if(trim($remark) === '')
        {
            return $this->json_response(array('response' => FALSE, 'message' => 'Remark is required.'), 422);
        }

        $result = $this->Ai_agent_model->enhance_remark($remark);
        if(!$result['success'])
        {
            return $this->json_response(array(
                'response' => FALSE,
                'message' => $result['message'],
                'http_code' => $result['http_code'],
                'raw_response' => $result['raw_response']
            ), 502);
        }
       // print_r($result);die;
        $details = $this->Ai_agent_model->insert_enhance_remark([
            'raw_remark' => $remark,
            'enhanced_remark' => $result['enhanced_remark']
        ]);
        return $this->json_response(array(
            'response' => TRUE,
            'enhanced_remark' => $result['enhanced_remark'],
            'provider' => $result['provider']
        ));
    }

    private function posted_remark()
    {
        $remark = $this->input->post('remark', TRUE);
        if($remark !== NULL)
        {
            return $remark;
        }

        $decoded = json_decode($this->input->raw_input_stream, TRUE);
        if(is_array($decoded) && isset($decoded['remark']))
        {
            return $decoded['remark'];
        }

        return '';
    }

    private function json_response($payload, $status_code = 200)
    {
        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload));
    }

    private function send_cors_headers()
    {
        $allowed = $this->config->item('ai_agent_allowed_origins', 'ai_agent');
        if(!is_array($allowed) || empty($allowed) || !isset($_SERVER['HTTP_ORIGIN']))
        {
            return;
        }

        $origin = $_SERVER['HTTP_ORIGIN'];
        if(in_array($origin, $allowed))
        {
            header('Access-Control-Allow-Origin: '.$origin);
            header('Vary: Origin');
            header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
            header('Access-Control-Allow-Methods: POST, OPTIONS');
        }
    }
}
