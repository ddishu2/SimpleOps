<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of reports_php
 *
 * @author vkhisty
 */
class report extends CI_controller {
    const C_RTYPE = 'type';
    const C_FROM_DATE = 'so_from_date';
    const C_TO_DATE = 'so_to_date';
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('m_report');
    }
    
    public function setreports()
    {
        
        echo "Hello";
       $rp_type      =  $this->input->get(self::C_RTYPE); 
       $rp_from_date =  $this->input->get(self::C_FROM_DATE); // Start Date
       $rp_to_date   =  $this->input->get(self::C_TO_DATE); // End Date
//       
        $this->m_report->isreportvalid($rp_type,$rp_from_date,$rp_to_date);
        $this->m_report->download();
        
        

    }
    public function demo()
    {
        echo "Hello";
    }
}
