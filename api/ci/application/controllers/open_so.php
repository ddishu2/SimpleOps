<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class open_so extends CI_controller
{
   //so_from_date=2015-09-11&so_to_date=2015-09-22&proj_name=&proj_bu=&proj_loc[]=&proj_id=&cust_name=&capability=
    Const C_PROJ_NAME = 'proj_name';
    const C_PROJ_LOC  = 'proj_loc';
    const C_PROJ_ID   = 'proj_id';
    const C_CUST_NAME = 'cust_name';
    const C_CAPABILITY = 'capability';
    

    const C_PROJ_BU = 'proj_bu';
    const C_TYPE = 'so_type';
   
    
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
       $lv_project_name = $this->input->get(self::C_PROJ_NAME);
       $lv_project_bu = $this->input->get(self::C_PROJ_BU);
       $lv_arr_locs = $this->input->get(self::C_PROJ_LOC) ;
       $lv_capability = $this->input->get(self::C_CAPABILITY);
       $lv_proj_id = $this->input->get(self::C_PROJ_ID);
       $lv_cust_name = $this->input->get(self::C_CUST_NAME);
       $lv_type = $this->input->get(self::C_TYPE);
       
      
       $filtered_so_locs = array_filter($larr_so_locs); 
       
        $lt_open_sos = [];
        $this->m_open_so->set_attributes($so_from_date, $so_to_date,$lv_project_name,$lv_project_bu,$filtered_so_locs,$lv_capability,$lv_proj_id,$lv_cust_name,$lv_type);
        $lt_open_sos = $this->m_open_so->get();
        echo json_encode($lt_open_sos, JSON_PRETTY_PRINT);
    }
    
}