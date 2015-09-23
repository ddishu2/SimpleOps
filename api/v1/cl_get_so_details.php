<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_get_so_details
 *
 * @author dikmishr
 */

class cl_get_so_details 
{    
    public $lv_so_no,
           $lv_emp_id,
           $lv_query_so,
           $lv_query_emp,
           $lv_query_acttype,
           $lt_so_details = [],
           $lt_emp_details = [];
    
    private function set_query($i_so_number = '', $i_emp_id = '')
        {
        
            $this->lv_so_no         =   $i_so_number;
            $this->lv_emp_id        =   $i_emp_id;
            $this->lv_query_so      =   'SELECT *
                                         FROM m_so_rrs 
                                         WHERE so_no ='.$i_so_number.' ORDER BY so_no';
            
            $this->lv_query_emp    =    'SELECT *
                                         FROM m_emp_ras
                                         WHERE emp_id = '.$i_emp_id.' ORDER BY emp_id';            
        }
        
    public function get_so_details($i_so_number)
        {
            self::set_query($i_so_number);
        
// Get SO details             
            $this->lt_so_details  = cl_DB::getResultsFromQuery($this->lv_query_so) 
                   or exit('No data found for SO '.$this->lv_so_no);      
        }
                
    public function get_emp_details($i_emp_id) 
        {
            self::set_query('',$i_emp_id);
            
// Get employee details.
            $this->lt_emp_details = cl_DB::getResultsFromQuery($this->lv_query_emp)
                   or exit('No employee found for EMP ID '.$this->lv_emp_id);
        }        
}
