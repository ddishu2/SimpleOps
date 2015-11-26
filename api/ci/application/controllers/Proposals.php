<?php

    session_start();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_Proposals
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class Proposals extends CI_Controller
{  
    const C_SO_ID = 'so_id';
    const C_SKILL = 'skill';
    const C_LEVEL = 'level';
    const C_LOC = 'loc';
    private static $arr_perfect_proposals = [];
    private static $arr_no_perfect_proposals = [];

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('m_open_so');
        $this->load->model('m_proposals');
        $this->load->model('m_BuEmployees');
        $this->load->model('m_lock');
    }
    
    public function deployable_emps()
    {
       $so_from_date   =   $this->input->get(m_open_so::C_FROM_DATE);
       $so_to_date      =   $this->input->get(m_open_so::C_TO_DATE);
       $lv_so_projname  =   $this->input->get(m_open_so::C_PROJ_NAME);
       $lv_so_proj_bu   =   $this->input->get(m_open_so::C_PROJ_BU);
       $larr_so_locs    =   $this->input->get(m_open_so::C_PROJ_LOC);
       $fp_v_proj_id    =   $this->input->get(m_open_so::C_PROJ_ID);
       $fp_v_capability =   $this->input->get(m_open_so::C_CAPABILITY);
       $fp_v_cust_name  =   $this->input->get(m_open_so::C_CUST_NAME);
       $fp_type = $this->input->get(m_open_so::C_TYPE);        
//       echo 'start date' .$so_from_date;
//       echo 'end date'.$so_to_date;
//        echo "Project name  ".$lv_so_projname."</br>";
//       echo "project BU    ". $lv_so_proj_bu."</br>";
////       echo $lv_arr_locs     ."</br>";
//       echo "capability    ".$fp_v_capability     ."</br>";
//       echo "project_id    ".$fp_v_proj_id      ."</br>";
//       echo "customer_name ".$fp_v_cust_name     ."</br>";
//       echo "so type       ". $fp_type     ."</br>";
      
       $filtered_so_locs = array_filter($larr_so_locs); 
//        $lt_deployable_emps = [];
        $this->m_open_so->set_attributes($so_from_date, $so_to_date,$lv_so_projname,$lv_so_proj_bu,$filtered_so_locs,$fp_v_capability,$fp_v_proj_id,$fp_v_cust_name,$fp_type);
        //$this->m_BuEmployees->set_attributes();
        $this->m_proposals->set_attributes($this->m_open_so,$this->m_BuEmployees);
        
        $lt_deployable_emps = $this->m_proposals->getAutoProposals();

        $this->m_proposals->segregateProposals($lt_deployable_emps);
//        $so_with_employee = $this->m_proposals->getSOWithEmployee();
//        $so_no_perfect_proposal = $this->m_proposal->getSOWithNoEmployee();
        self::$arr_perfect_proposals = $this->m_proposals->getSOWithEmployee();
        self::$arr_no_perfect_proposals = $this->m_proposals->getSOWithNoEmployee();
              
       // $this->load->library('session');
       // $this->session->set_mProposals($this->m_proposals->getSOWithEmployee());
       $_SESSION['m_proposals'] = $this->m_proposals->getSOWithNoEmployee();
     
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lt_deployable_emps,JSON_PRETTY_PRINT));

//       echo "so with match". "<BR>";  
//        $this->output
//        ->set_content_type('application/json')
//        ->set_output(json_encode($so_wit_employee,JSON_PRETTY_PRINT));
        
//        echo "<br>SO for partial proposals<br>";
//      
////      echo "so with no match"."<BR>";   
//        $this->output
//        ->set_content_type('application/json')
//        ->set_output(json_encode($SO_no_employee_proposed,JSON_PRETTY_PRINT));
        
    }
    public function getPartialProposals()
    {
        
        $lv_so_id = $this->input->get(self::C_SO_ID);
        $lv_so_skill = $this->input->get(self::C_SKILL);
        $lv_so_level = $this->input->get(self::C_LEVEL);
        $lv_so_loc = $this->input->get(self::C_LOC);
        
//        echo $lv_so_loc;
        $this->m_proposals->set_attributes($this->m_open_so,$this->m_BuEmployees,'','','','','','');
        $lt_partially_proposed = $this->m_proposals->getpartialProposals($lv_so_id,$lv_so_skill,$lv_so_level,$lv_so_loc);
     
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lt_partially_proposed,JSON_PRETTY_PRINT));
    }
    public function getSoForPartialProposal()
    {
        $arr_no_perfect_proposal = $_SESSION['m_proposals'];
       // print_r($arrM_proposals);
        
        session_unset($_SESSION['m_proposals']);
       //$larr_so_with_no_perfect_proposal = $this->m_proposals->getSOWithNOEmployee();
//      print_r(self::$arr_no_perfect_proposals);
        $this->output
        ->set_content_type('application/json')
//        ->set_output(json_encode($larr_so_with_no_perfect_proposal,JSON_PRETTY_PRINT));
                
                 ->set_output(json_encode($arr_no_perfect_proposal,JSON_PRETTY_PRINT));
    }
}
