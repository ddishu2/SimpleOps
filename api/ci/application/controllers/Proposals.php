<?php

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
               
//       echo 'start date' .$so_from_date;
//       echo 'end date'.$so_to_date;
       
        $lt_deployable_emps = [];
        $this->m_open_so->set_attributes($so_from_date, $so_to_date);
        //$this->m_BuEmployees->set_attributes();
        $this->m_proposals->set_attributes($this->m_open_so,$this->m_BuEmployees,$lv_so_projname,$lv_so_proj_bu,$larr_so_locs,$fp_v_proj_id,$fp_v_capability,$fp_v_cust_name);
        
        $lt_deployable_emps = $this->m_proposals->getAutoProposals();
       
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lt_deployable_emps,JSON_PRETTY_PRINT));

        //echo json_encode($lt_deployable_emps,JSON_PRETTY_PRINT);
        
        
    }
}
