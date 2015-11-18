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
    
    
    protected $v_so_sdate;
    protected $v_so_endate;
    private $arr_open_sos = [];
    const C_FNAME_SO_POS_NO = 'so_pos_no';
    const C_FNAME_SKILL ='so_primary_skill';
    const C_FNAME_LOCATION = 'so_loc' ;
    const C_FNAME_LEVEL = 'so_level';
    const C_FNAME_START_DATE = 'so_start_date_new';
    const C_FNAME_END_DATE = 'so_end_date';
    const C_FNAME_PROJ_ID = 'so_proj_id';
    const C_FNAME_PROJ_NAME = 'so_proj_name';
    
    public function __construct()
        {
          
                $this->load->database();
//                echo "inside INdex";
        }
    public function set_attributes($fp_so_start_date,$fp_so_end_date)
    {
       $this->v_so_sdate = $fp_so_start_date;
       $this->v_so_endate = $fp_so_end_date;     
    }
    public function get()
    {
        $this->set();  
        return $this->arr_open_sos;
    }
    private function set()
    {
         $larr_open_sos = [];
//        $lv_query = parent::getQuery();
//        $larr_sos = cl_DB::getResultsFromQuery($lv_query);
         $this->db->where(self::C_FNAME_START_DATE." BETWEEN CAST('$this->v_so_sdate' AS DATE)AND CAST('$this->v_so_endate' AS DATE)");
         
         $query = $this->db->get('v_fulfill_stat_open');
                //return $query->result_array();
         
         
        foreach ($query->result_array() as $lwa_so) 
        {
            $lv_so_id = $lwa_so[self::C_FNAME_SO_POS_NO];
            $larr_open_sos[$lv_so_id] = $lwa_so;
        }
        $this->arr_open_sos =  $larr_open_sos;
        
    }
    
}