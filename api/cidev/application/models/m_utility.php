<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilities
 *
 * @author Dikshant Mishra/Dikmishr
 */

require (APPPATH.'models/m_loadFiles.php');
require_once(APPPATH.'libraries/l_slock_expiry.php');

require_once(APPPATH.'models/m_Notifications.php');

class m_utility extends CI_model
{
    const gc_business_days  = 22,
          gc_date_format    = 'Y-m-d',
          gc_date_from      = 'date_from',
          gc_bu             = 'Appsone SAP',
          gc_filepath       = '\\\\10.75.250.149\AppsOne_SAP_Operations$\009_SAP Dashboards\Bench Ageing Data\\',
          gc_date_year      = 'Y',
          gc_default_format = 'Y-m-d H:i:s',
          gc_filename       = '-BENCH_AGEING_DATA.xlsm',
          gc_date_ba        = 'd F',
          gc_date_month     = 'F';
            
    private $gv_tab_name      = 'm_emp_ras_copy',
            $gv_so            = 'curr_so',          
            $gv_edate         = 'curr_end_date',
            $gv_idp           = 'idp',
            $gv_sub_bu        = 'sub_bu',
            $gv_svc_line      = 'svc_line',
            $gv_org           = 'org',
            $gv_empid         = 'emp_id',
            $gv_emp_name      = 'emp_name',
            $gv_prime_skill   = 'prime_skill',
            $gv_proj_code     = 'curr_proj_code',
            $gv_proj_name     = 'curr_proj_name',
            $gv_level         = 'level',
            $gv_sup_id        = 'sup_id',
            $gv_sup_name      = 'sup_name',
            $gv_pm_name       = 'proj_m_name',
            $gv_cust_name     = 'cust_name',
            $gv_pm_id         = 'proj_m_id',
            $gv_em_id         = 'eng_mgr_id',
            $gv_em_name       = 'eng_mgr_name';
    
    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_username() 
    {
    $lv_cred = explode('\\', $_SERVER['REMOTE_USER']);
    $lv_domain_id = $lv_cred[0];
    $io_query = $this->db->query("SELECT emp_name FROM m_emp_ras WHERE domain_id = ?", $lv_domain_id);
    $lt_name = $io_query->result_array();
    foreach ($lt_name as $lwa_name) 
        {
            $lv_cred[0] = $lwa_name['emp_name'];
        }
    return $lv_cred;
    }

    private function add_business_days($i_sdate) 
    {   $io_date = new DateTime($i_sdate);
        $io_date->modify('+'.self::gc_business_days.' weekdays');
        while (($io_date->format('N')) > 5)
        {
            $io_date->modify('+1 day');
        }
        return ($io_date->format(self::gc_date_format));
    }
    
// Function to get all the hard locks which will be released on a particular date.
    private function getreleasablehardlocks()
    {   
        $lt_invalid_project = "('Bench','Campus Hire', 'Apps1 Long  Leave & Ml')";
        $lv_edate = $this->add_business_days(date(self::gc_date_format));
        $lv_query_empid =  'SELECT '. $this->gv_so.','.
                            $this->gv_edate.','.
                            $this->gv_idp.','.
                            $this->gv_sub_bu.','.
                            $this->gv_svc_line.','.
                            $this->gv_org.','.
                            $this->gv_empid.','.
                            $this->gv_emp_name.','.
                            $this->gv_prime_skill.','.
                            $this->gv_proj_code.','.
                            $this->gv_proj_name.','.
                            $this->gv_level.','.
                            $this->gv_sup_id.','.
                            $this->gv_sup_name.','.
                            $this->gv_pm_id.','.
                            $this->gv_pm_name.','.
                            $this->gv_em_id.','.
                            $this->gv_em_name.
                            ' FROM '.$this->gv_tab_name.
                            " WHERE curr_end_date = '$lv_edate' and ".
                            $this->gv_idp . " = 'Appsone SAP' and "
                            .$this->gv_cust_name." NOT IN " .$lt_invalid_project." ORDER BY ". $this->gv_proj_code.','.$this->gv_sup_id;          
        return($this->db->query($lv_query_empid)->result_array());                 
    }
    
    public function checkandnotify()
    {   
        $lv_prev_proj_code = '';
        $lt_proj_details   = [];
        $lt_sup_details    = [];
        $lt_emp_details = $this->getreleasablehardlocks();
        $lv_count = count($lt_emp_details);
        if ($lv_count > 0) 
            {
            foreach ($lt_emp_details as $lv_key => $lwa_values) 
                {
                
// Check if the current record is the last record in the table for that field's value.                                                                
// If its the last record, we'll collect the current record and send out email notifications.
// Then we'll clear the table to be used for next set of records. 
                    if($this->atendofvalue($lt_emp_details, $lv_key, $lwa_values, $this->gv_proj_code))
                    {
                    array_push($lt_proj_details, $lwa_values);
                    foreach($lt_proj_details as $lv_key_proj => $lwa_values_proj)
                    if($this->atendofvalue($lt_proj_details, $lv_key_proj, $lwa_values_proj, $this->gv_sup_id))
                    {
                      array_push($lt_sup_details, $lwa_values_proj);
                      $io_mail = new m_Notifications(); 
                      $lv_mail = $io_mail->sendhardlockreleasenotification($lt_sup_details);                     
                      $lt_sup_details = [];
                      $lwa_values_proj = [];
                    }                    
                    else
                    {array_push($lt_sup_details, $lwa_values_proj);
                    }
                    $lt_proj_details = [];
                    $lwa_values      = [];
                    }                                    
// If its not the last record then we'll just collect the current record and 
// wait for the last record to come                     
                    else
                    {array_push($lt_proj_details, $lwa_values);}
                }
            }  
        }
            
// Method to check if supplied row is the last record with value of the field same as itself in the table           
    private function atendofvalue($i_table, $i_key, $i_row, $i_field)
    {       
// Increment the key
        $lv_key_new = $i_key + 1;
        if ((array_key_exists($lv_key_new, $i_table) ===  true) && ( $i_table[$lv_key_new][$i_field] == $i_row[$i_field])) 
        {return false;}
        else 
        {return true;}
    }
        
// Get Bench Ageing File path
    public function BAfilepath()
    {
        $lv_filepath  = self::gc_filepath;
        $lv_year      = date(self::gc_date_year);
        $lv_filepath .= $lv_year.DIRECTORY_SEPARATOR.date(self::gc_date_month).DIRECTORY_SEPARATOR.date(self::gc_date_ba).self::gc_filename;
        return $lv_filepath;
    }
    
// DateTime validator function
    public function validateDate($i_date, $i_format = self::gc_default_format)
    {   
    $io_date = DateTime::createFromFormat($i_format, $i_date);
    return $io_date && $io_date->format($i_format) == $i_date;
    }
    
// Load RAS and RRS files    
    public function loadRAS()
    {
    $loadRAS = m_loadFiles::loadRAS();
    }
    public function loadFULLFILLSTAT()
    {
    $loadRRS = m_loadFiles::loadFULLFILLSTAT();
    }   
    public function loadAmendments()
    {
      $loadAmendments = m_loadFiles::loadAmendments();  
    }
    
    public function getslockexpiry($fp_so_id){
        l_slock_expiry::slock_expiry_details($fp_so_id);
    }
}

