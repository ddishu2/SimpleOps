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
        $lv_tabname,
        $lv_query_alias,
        $lt_so_details = [],
        $lt_emp_details = [],
        $lt_corpid_details = [];
    
private function set_query($i_so_number = '', $i_emp_id = '', $i_emp_alias = '', $i_tabname = '')       
    {        
        $this->lv_so_no         =   $i_so_number;
        $this->lv_emp_id        =   $i_emp_id;
        $this->lv_query_so      =   'SELECT *
                                    FROM m_so_rrs 
                                    WHERE so_no ='.$i_so_number.' LIMIT 1';
        
        if ($i_tabname === '')
        {
            $this->lv_tabname = 'm_emp_rrs';
        }
        else
        {
            $this->lv_tabname = $i_tabname;
        }    
            
        $this->lv_query_emp    =    "SELECT *
                                    FROM $this->lv_tabname
                                    WHERE emp_id = '$i_emp_id' LIMIT 1";
            
        $this->lv_query_alias  =    "SELECT * FROM $this->lv_tabname WHERE domain_id = '$i_emp_alias' LIMIT 1";
    }
        
public function get_so_details($i_so_number)
    {
        self::set_query($i_so_number,'','','');
        
// Get SO details             
        $this->lt_so_details  = cl_DB::getResultsFromQuery($this->lv_query_so);
        return $this->lt_so_details;             
    }
                
public function get_emp_details($i_emp_id,$i_tabname = '') 
    {
        self::set_query('',$i_emp_id, '',$i_tabname);
            
// Get employee details.
        $this->lt_emp_details = cl_DB::getResultsFromQuery($this->lv_query_emp);
        return $this->lt_emp_details;       
    } 
        
// Get Employee details based on CORP ID
public function get_corpid_details($i_emp_alias,$i_tabname = '')
    {       
        
    $this->lt_corpid_details  = [];     
    self::set_query('','', $i_emp_alias, $i_tabname);
            
// Get Corp-ID Details            
        if ( $this->lt_corpid_details = cl_DB::getResultsFromQuery($this->lv_query_alias) )
        { return $this->lt_corpid_details; }
    }   
}
