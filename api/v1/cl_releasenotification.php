<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_workingdays
 *
 * @author Dikshant Mishra/dikmishr
 */
class cl_releasenotification {
    const gc_business_days = 23,
          gc_date_format   = 'd-M-Y',
          gc_date_from     = 'date_from';
            
    private $gv_so            = 'curr_so',          
            $gv_edate         = 'cur_end_date',
            $gv_idp           = 'idp',
            $gv_sub_bu        = 'sub_bu',
            $gv_svc_line      = 'svc_line',
            $gv_org           = 'org',
            $gv_empid         = 'emp_id',
            $gv_emp_name      = 'emp_name',
            $gv_prime_skill   = 'prime_skill',
            $gv_proj_code     = 'curr_proj_code',
            $gv_proj_name     = 'curr_proj_name',
            $gv_level         = 'level';
    
    private function add_business_days($i_sdate) {
        $lv_count = 1;
        $lv_dayx = strtotime($i_sdate);
        while ($lv_count < self::gc_business_days) {
                $lv_day = date('N', $lv_dayx);
                $lv_date = date('Y-m-d', $lv_dayx);
                if ($lv_day < 6)
                $lv_count++;
                $lv_dayx = strtotime($lv_date . ' +1 day');
        }
        return date(self::gc_date_format, $lv_dayx);
    }
    
// Function to get all the hard locks which will be released on a particular date.
    private function getreleasablehardlocks()
    {
        $lv_edate = $this->add_business_days(date(self::gc_date_format));       
        $lv_query_empid =  "SELECT '$this->gv_so',
                            '$this->gv_edate',
                            '$this->gv_idp',
                            '$this->gv_sub_bu',
                            '$this->gv_svc_line,
                            '$this->gv_org',
                            '$this->gv_empid',
                            '$this->gv_emp_name',
                            '$this->gv_prime_skill',
                            '$this->gv_proj_code',
                            '$this->gv_proj_name',
                            '$this->gv_level',
                            FROM m_emp_ras
                            WHERE curr_end_date = '$lv_edate' and
                            '$this->gv_idp' = 'Appsone SAP' ORDER BY '$this->gv_proj_code'";
        $lt_emp_details = cl_DB::getResultsFromQuery($lv_query_empid);
        return $lt_emp_details;
    }
    
    public function checkandnotify()
    {   
        $lv_prev_proj_code = '';
        $lt_proj_details   = [];
        $lt_emp_details = $this->getreleasablehardlocks();
        $lv_count = count($lt_emp_details);
        if ($lv_count > 0) 
            {
            foreach ($lt_emp_details as $lv_key => $lwa_values) 
                {
                $lv_index = array_search($lwa_values[$this->gv_proj_code], array_column($lt_proj_details, $this->gv_proj_code));
                }
            }
    }
}


$io = new cl_releasenotification();
$io->checkandnotify();