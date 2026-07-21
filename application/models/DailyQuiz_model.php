<?php

class DailyQuiz_model extends CI_Model
{
    public function getQuestions($category)
    {

        $this->db->where('category',$category);
        $this->db->where('is_active',1);

        $this->db->order_by('RAND()');

        $this->db->limit(10);

        return $this->db->get('daily_questions')->result();

    }
}
