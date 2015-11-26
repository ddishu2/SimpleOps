<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of m_ManualLocks
 *
 * @author dikmishr
 */

require_once(APPPATH.'models/m_utility.php');
class m_ManualLocks extends CI_model
{   
    const gc_date_format    = 'y-mm-dd',
          gc_so_pos_no      = 'so_pos_no',
          gc_so_prim_skill  = 'so_primary_skill',
          gc_so_loc         = 'so_loc',
          gc_so_level       = 'so_level',
          gc_so_sdate_new   = 'so_start_date_new',
          gc_so_edate       = 'so_end_date',
          gc_so_proj_id     = 'so_proj_id',
          gc_so_proj_name   = 'so_proj_name',
          gc_so_proj_bu     = 'so_proj_bu',
          gc_cust_name      = 'cust_name',
          gc_so_capability  = 'so_capability',
          gc_viewname       = 'v_fulfill_stat_open',
          gc_so_proj_type   = 'so_proj_type';
    
    
    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_ValidSOs(   $i_from_date = '', 
                                    $i_to_date = '', 
                                    $i_proj_bu = '', 
                                    $i_type = '', 
                                    $i_proj_name = '', 
                                    $i_proj_loc = '', 
                                    $i_proj_id = '',
                                    $i_cust_name = '',
                                    $i_capability = ''  )
    {
        
// Select SO Number from table        
        $this->db->select(self::gc_so_proj_id.','.self::gc_so_proj_name.','.self::gc_cust_name.','.self::gc_so_proj_bu.','.self::gc_so_pos_no);
        
// Instantiate utility model and use validateDate() to validate the input date format        
        $io_utility = new m_utility();
        
// Check for filters and apply them if they're set.        
        if((($this->isFilterset($i_from_date)) && ($io_utility->validateDate($i_from_date, self::gc_date_format)) === true) &&
           (($this->isFilterset($i_to_date))   && ($io_utility->validateDate($i_to_date, self::gc_date_format)) === true)) 
        {
        $this->db->where(self::gc_so_sdate_new." BETWEEN CAST('$i_from_date' AS DATE)AND CAST('$i_to_date' AS DATE)");
        }
        if($this->isFilterset($i_proj_name))
        {
        $this->db->like(self::gc_so_proj_name, $i_proj_name, 'both');    
        }
        if($this->isFilterset($i_proj_bu))
        {
        $this->db->where(self::gc_so_proj_bu,$i_proj_bu);
        }        
        if($this->isFilterset($i_proj_loc))
        {
        $this->db->where_in(self::gc_so_loc,$i_proj_loc);
        }         
        if($this->isFilterset($i_capability))
        {
        $this->db->where(self::gc_so_capability,$i_capability);
        }
        if($this->isFilterset($i_proj_id))
        {
        $this->db->like(self::gc_so_proj_id, $i_proj_id, 'both'); 
        }
        if($this->isFilterset($i_cust_name))
        {
        $this->db->like(self::gc_cust_name, $i_cust_name, 'both'); 
        }
        if($this->isFilterset($i_type))
        {
        $this->db->where(self::gc_so_proj_type,$i_type);
        }
        
// Once all filters are set, query the view and return the array.
        return($this->db->get(self::gc_viewname)->result_array());
    }
    
    public function get_ValidEMPs()
    {
        
    }
    
    public function Lock_EMPs()
    {
        
    }
    private function isFilterset($fp_filter_value)
    {
        $filter_set = false;
       if(!($fp_filter_value == ''|| $fp_filter_value == null)){
            $filter_set = true;
       }
       return $filter_set;
    }
}
