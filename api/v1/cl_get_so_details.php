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
    private $lv_so_no,
            $lv_emp_id,
            $lv_query_so,
            $lv_query_emp,
            $lv_query_alias;
    
    Public  $lt_so_details = [],
            $lt_emp_details = [],
            $lt_corpid_details = [];
    
    private function set_query($i_so_number = '', $i_emp_id = '', $i_emp_alias = '')
        {
        
            $this->lv_so_no         =   $i_so_number;
            $this->lv_emp_id        =   $i_emp_id;
            $this->lv_query_so      =   'SELECT *
                                         FROM m_so_rrs 
                                         WHERE so_no ='.$i_so_number.' LIMIT 1';
            
            $this->lv_query_emp    =    "SELECT *
                                         FROM m_emp_ras
                                         WHERE emp_id = '$i_emp_id' LIMIT 1";
            
            $this->lv_query_alias  =    "SELECT * FROM m_emp_ras WHERE domain_id = '$i_emp_alias' LIMIT 1";
        }
        
    public function get_so_details($i_so_number)
        {
            self::set_query($i_so_number);
        
// Get SO details             
            $this->lt_so_details  = cl_DB::getResultsFromQuery($this->lv_query_so) 
                   or exit('No data found for SO '.$i_so_number);      
        }
                
    public function get_emp_details($i_emp_id) 
        {
            self::set_query('',$i_emp_id, '');
            
// Get employee details.
            $this->lt_emp_details = cl_DB::getResultsFromQuery($this->lv_query_emp)
                   or exit('No employee found for EMP ID '.$i_emp_id);
        } 
        
// Get Employee details based on CORP ID
    public function get_corpid_details($i_emp_alias)
        {       
        
// Defaulting Employee alias since no match was found. By Dikshant Mishra(10/07/2015)        
        $i_emp_alias = 'mmittapa';
            self::set_query('','', $i_emp_alias);
            
// Get Corp-ID Details            
            $this->lt_corpid_details = cl_DB::getResultsFromQuery($this->lv_query_alias)
                    or exit('No employee found for CORP-ID '.$i_emp_alias);
            

//            $this->lt_corpid_details = cl_DB::getResultsFromQuery("SELECT * FROM m_emp_ras WHERE domain_id = 'vgannama' LIMIT 1");
           
                    
//                   or exit('No employee found for CORP-ID '.$i_emp_alias);
        }    
}
