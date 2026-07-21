<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DailyQuiz extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('DailyQuiz_model');
    }


    public function index()
    {

        $data['aptitude'] = $this->DailyQuiz_model->getQuestions('aptitude');
        $data['reasoning'] = $this->DailyQuiz_model->getQuestions('reasoning');
        $data['vocabulary'] = $this->DailyQuiz_model->getQuestions('vocabulary');
        $data['gk'] = $this->DailyQuiz_model->getQuestions('gk');

        $this->load->view('daily_quiz_view',$data);

    }


}