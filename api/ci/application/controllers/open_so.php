<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class open_so extends CI_controller
{
   
    Const C_PROJ_NAME = 'proj_name';
    const C_PROJ_LOC  = 'proj_loc';
    const C_PROJ_ID   = 'proj_id';
    const C_CUST_NAME = 'cust_name';
    const C_CAPABILITY = 'capability';
    
    
    
    
    const C_FROM_DATE = 'so_from_date';
    const C_TO_DATE = 'so_to_date';
      public function __construct() 
    {
        parent::__construct();
        $this->load->model('m_open_so');
    }
    public function getOpenSO()
    {
       $so_from_date =  $this->input->get(self::C_FROM_DATE);
       $so_to_date =  $this->input->get(self::C_TO_DATE);
       
//       echo 'start date' .$so_from_date;
//       echo 'end date'.$so_to_date;
       
        $lt_open_sos = [];
        $this->m_open_so->set_attributes($so_from_date, $so_to_date);
        $lt_open_sos = $this->m_open_so->get();
        echo json_encode($lt_open_sos, JSON_PRETTY_PRINT);
    }
}