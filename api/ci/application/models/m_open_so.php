<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class m_open_so extends CI_model
{
    
     
    Const C_PROJ_NAME = 'proj_name';
    const C_PROJ_LOC  = 'proj_loc';
    const C_PROJ_ID   = 'proj_id';
    const C_CUST_NAME = 'cust_name';
    const C_CAPABILITY = 'capability'; 
    const C_FROM_DATE = 'so_from_date';
    const C_TO_DATE = 'so_to_date';
    const C_PROJ_BU = 'so_proj_bu';
    const C_TYPE = 'so_type';
    
    protected $v_so_sdate;
    protected $v_so_endate;
    protected $v_project_name;
    protected $v_project_bu;
    protected $arr_locs;
    protected $v_capability;
    protected $v_project_id;
    protected $v_customer_name;
    protected $v_so_type;
    
    
    private $arr_open_sos = [];
    const C_FNAME_SO_POS_NO = 'so_pos_no';
    const C_FNAME_SKILL ='so_primary_skill';
    const C_FNAME_LOCATION = 'so_loc' ;
    const C_FNAME_LEVEL = 'so_level';
    const C_FNAME_START_DATE = 'so_start_date_new';
    const C_FNAME_END_DATE = 'so_end_date';
    const C_FNAME_PROJ_ID = 'so_proj_id';
    const C_FNAME_PROJ_NAME = 'so_proj_name';
    const C_FNAME_PROJECT_BU = 'so_proj_bu';
    const C_FNAME_CUSTOMER_NAME = 'cust_name';
    const C_FNAME_CAPABILITY = 'so_capability';
    const C_TABNAME = 'v_fulfill_stat_open';
    const C_FNAME_PROJ_TYPE = 'so_proj_type';
    
//    $fp_o_open_sos->filterByContainsProjectName($lv_so_projname);
//        $fp_o_open_sos->filterByEqualsProjBU($lv_so_proj_bu);
//        $fp_o_open_sos->filterByInLocationList($larr_so_locs);
//        $fp_o_open_sos->filterByContainsProjectID($fp_v_proj_id);
//        $fp_o_open_sos->filterByEqualsCapability($fp_v_capability);
//        $fp_o_open_sos->filterByContainsCustomerName($fp_v_cust_name);
    
    public function __construct()
        {
          
                $this->load->database();
//                echo "inside INdex";
        }
    public function set_attributes($fp_so_start_date,$fp_so_end_date,$fp_project_name,$fp_project_bu,$fp_arr_locs,$fp_capability,$fp_proj_id,$fp_cust_name,$fp_type)
    {
       $this->v_so_sdate = $fp_so_start_date;
       $this->v_so_endate = $fp_so_end_date;  
       $this->v_project_name = $fp_project_name;
       $this->v_project_bu = $fp_project_bu;
       $this->arr_locs = $fp_arr_locs;
       $this->v_capability = $fp_capability ;
       $this->v_project_id= $fp_proj_id;
       $this->v_customer_name = $fp_cust_name ;
       $this->v_so_type = $fp_type;
    }
    public function get()
    {
        $this->set();  
        return $this->arr_open_sos;
    }
    private function set()
    { 
//        echo $this->v_project_bu;
         $larr_open_sos = [];
//        $lv_query = parent::getQuery();
//        $larr_sos = cl_DB::getResultsFromQuery($lv_query);
         $this->db->where(self::C_FNAME_START_DATE." BETWEEN CAST('$this->v_so_sdate' AS DATE)AND CAST('$this->v_so_endate' AS DATE)");
         
         if($this->isFilterset($this->v_project_name))
         {
          //$this->db->where(self::C_FNAME_PROJ_NAME,$this->v_project_name);
          $this->db->like(self::C_FNAME_PROJ_NAME, $this->v_project_name, 'both'); 
         } 
        if($this->isFilterset($this->v_project_bu))
         {
            $this->db->where(self::C_FNAME_PROJECT_BU,$this->v_project_bu);
         }        
         if($this->isFilterset($this->arr_locs))
          {
              $this->db->where_in(self::C_FNAME_LOCATION,$this->arr_locs);
          }         
         if($this->isFilterset($this->v_capability))
          {
              $this->db->where(self::C_FNAME_CAPABILITY,$this->v_capability);
          }
          if($this->isFilterset($this->v_project_id))
          {
              $this->db->like(self::C_FNAME_PROJ_ID, $this->v_project_id, 'both'); 
          }
           if($this->isFilterset($this->v_customer_name))
          {
              $this->db->like(self::C_FNAME_CUSTOMER_NAME, $this->v_customer_name, 'both'); 
          }
          if($this->isFilterset($this->v_so_type))
          {
              $this->db->where(self::C_FNAME_PROJ_TYPE,$this->v_so_type);
          }
         $query = $this->db->get(self::C_TABNAME);
                //return $query->result_array();
         
//         echo $this->db->last_query();
        foreach ($query->result_array() as $lwa_so) 
        {
            $lv_so_id = $lwa_so[self::C_FNAME_SO_POS_NO];
            $larr_open_sos[$lv_so_id] = $lwa_so;
        }
        $this->arr_open_sos =  $larr_open_sos;
        
    }
    private function isFilterset($fp_filter_value)
    {
        $filter_set = false;
       if(!($fp_filter_value == ''|| $fp_filter_value == null)){
            $filter_set = true;
       }
       return $filter_set;
    }
    private function isArrayEmpty($fp_arr)
    {
        $filter_set = false;
        
        
        
    }
}