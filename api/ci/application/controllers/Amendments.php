<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Amendments
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class Amendments extends CI_Controller
{
    const C_COMPETENCY = 'competency';
    
    const C_CUST_NAME = 'cust_name';
    const C_PROJ_NAME = 'proj_name';
    const C_COMMENTS = 'comments';
    const C_EMP_ID = 'emp_id';
    const C_STAT = 'status';
    public function __construct() 
    {
        parent::__construct();
//        $this->load->database();
        $this->load->model('m_getamendment');
        $this->load->model('m_approveamendment');
        $this->load->model('m_Notificarions');
    }
    
    public  function getamendments()
    {
    $lv_cust_name = $this->input->get(self::C_CUST_NAME);
    $lv_proj_name = $this->input->get(self::C_PROJ_NAME);                
    $lv_arr_competency = $this->input->get(self::C_COMPETENCY);
    $re_ammendments = $this->m_getamendment->getamnedments($lv_cust_name,$lv_proj_name,$lv_arr_competency);
//    $app->response->setStatus(200);
//    $app->response->headers->set('Content-Type', 'application/json');  
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($re_ammendments,JSON_PRETTY_PRINT));

     //echo json_encode($re_ammendments,JSON_PRETTY_PRINT);
    }


public function approveamendment()
{
    $lv_arr_emp_id = $this->input->get(self::C_EMP_ID);
    $lv_arr_comments = $this->input->get(self::C_COMMENTS);
    $lv_arr_stat = $this->input->get(self::C_STAT);
    
     $re_result = $this->m_approveamendment->approveammendments($lv_arr_emp_id, $lv_arr_comments,$lv_arr_stat);
     
     $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($re_result,JSON_PRETTY_PRINT));
}

}


