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
    public function getreleasablehardlocks()
    {
        $lv_edate = $this->add_business_days(date(self::gc_date_format));
        $lv_query_empid =   "SELECT curr_so,
                            curr_end_date,
                            idp,
                            sub_bu,
                            svc_line,
                            org,
                            emp_id,
                            emp_name,
                            prime_skill,
                            curr_proj_code,
                            curr_proj_name
                            level
                            from m_emp_ras
                            where curr_end_date = '$lv_edate'";
        $lt_emp_details = cl_DB::getResultsFromQuery($lv_query_empid);
        return $lt_emp_details;
    }
}


