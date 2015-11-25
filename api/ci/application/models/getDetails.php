<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class getDetails 
{ 
    
private 
    $lv_query_emp,
    $lv_tabname,
    $lv_query_alias;

//    const C_SO_MASTER = 'm_so_fulfill_stat';
    const C_SO_MASTER  = 'v_fulfill_stat_open';
    const C_EMP_MASTER = 'm_emp_ras';
    const C_EMP_ID = 'emp_id';
    const C_SO_POS_NO = 'so_pos_no';
    const C_FNAME_EMPNAME = 'emp_name';
    const C_EMP_PRIME_SKILL = 'prime_skill';
    const C_EMP_SVC_LINE = 'svc_line';
    const C_EMP_LVL = 'level';
    
    private function set_query($i_emp_id = '', $i_emp_alias = '', $i_tabname = '')       
    {  
        if ($i_tabname === '')
        {$this->lv_tabname = 'm_emp_ras';}
        else
        {$this->lv_tabname = $i_tabname;}    
            
        $this->lv_query_emp    = "SELECT * FROM ".$this->lv_tabname." WHERE emp_id = '$i_emp_id' LIMIT 1";            
        $this->lv_query_alias  = "SELECT * FROM $this->lv_tabname WHERE domain_id = '$i_emp_alias' LIMIT 1";
    }    
    public static function getSODetails($fp_v_so_no)
    {
//      $sql = "SELECT * FROM `m_so_rrs` WHERE so_no = $fp_v_so_no ";
//      $sql = "SELECT * FROM `m_so_fulfill_stat` WHERE so_pos_no = $fp_v_so_no ";
//      $lt_result = cl_DB::getResultsFromQuery($sql); 
       
        $ci_ins =& get_instance();
//      $ci_ins->db->query();
       
        $query = $ci_ins->db->get_where(self::C_SO_MASTER, array(self::C_SO_POS_NO => $fp_v_so_no));
//      echo $ci_ins->db->last_query();
        $lt_result = $query->result_array();
//      print_r($lt_result);
        return $lt_result;
    }
    public static function getEmpDetails($fp_v_emp_id)
    {
        $ci_ins =& get_instance();
//      $sql = "SELECT * FROM `m_emp_ras` WHERE emp_id = $fp_v_emp_id ";
//      $lt_result = cl_DB::getResultsFromQuery($sql); 
        $query = $ci_ins->db->get_where(self::C_EMP_MASTER , array(self::C_EMP_ID => $fp_v_emp_id));
        $lt_result = $query->result_array();
        return $lt_result;
    }
   
// Get Employee details based on employee ID from different table    
    public function get_emp_details($i_emp_id,$i_tabname = '') 
        {
        $ci_ins =& get_instance();
        self::set_query($i_emp_id,'',$i_tabname);
        return ($ci_ins->db->query($this->lv_query_emp)->result_array());  
        } 

// Get Employee details based on CORP ID
    public function get_corpid_details($i_emp_alias,$i_tabname = '')
        {         
        $ci_ins =& get_instance();
        self::set_query('',$i_emp_alias, $i_tabname);
        return ($ci_ins->db->query($this->lv_query_alias)->result_array());  
        }   
}