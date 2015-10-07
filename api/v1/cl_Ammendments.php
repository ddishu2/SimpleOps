<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class cl_ammendments {

    const C_COMMENTS = 'comments';
    const C_EMP_ID = 'emp_id';
    const C_STAT = 'status';
    const C_AMMEND_TABLE = 'ammend_table';

    public static function getAmmendments() {
        $re_ammendments = [];
        $sql = "SELECT * FROM `m_ammendment` ";
        $re_result = cl_DB::getResultsFromQuery($sql);
//      print_r($re_ammendments);

        foreach ($re_result as $key => $value) {


            if ((!$value['new_edate'] == '') || (!$value['new_sup_id'] == 0)) {
                $re_ammendments[] = $value;
            }
        }



        return $re_ammendments;
    }

    public function ApproveAmmendments($fp_Arr_result) {

        $lv_Approved_count = 0;
        $lv_reject_count = 0;
        $lv_res_count = [];
        //$lv_count = count($fp_Arr_result);
        // for ($i = 0; $i < $lv_count; $i++) {
        foreach ($fp_Arr_result as $key => $value) {
            //if ($fp_arr_stat[$i] == 'Approve')
            if ($value['status'] == 'Approve') {
                self::updateAmmendmentsandmail($value);

                $lv_Approved_count ++;
            } else if ($value['status'] == 'Reject') {
                self::updateAmmendmentsandmail($value);
                $lv_reject_count++;
            }
        }
        $lv_res_count['Approved'] = $lv_Approved_count;
        $lv_res_count['Rejected'] = $lv_reject_count;

        return $lv_res_count;
    }

    private function updateAmmendmentsandmail($fp_arr_result) {

        $lv_id = $fp_arr_result['id'];
        $lv_name = $fp_arr_result['name'];
        $lv_level = $fp_arr_result['level'];
        $lv_IDP = $fp_arr_result['IDP'];
        $lv_loc = $fp_arr_result['loc'];
        $lv_bill_stat = $fp_arr_result['bill_stat'];
        $lv_competency = $fp_arr_result['competency'];
        $lv_curr_proj_name = $fp_arr_result['curr_proj_name'];




        //change string date to date format 
        $lv_csdate = $fp_arr_result['curr_sdate'];
        $lv_curr_sdate = date('y-m-d', strtotime($lv_csdate));


        //change string date to date format 
        $lv_cedate = $fp_arr_result['curr_edate'];
        $lv_curr_edate = date('y-m-d', strtotime($lv_cedate));




         //change string date to date format 
        $lv_proj_edate = $fp_arr_result['proj_edate_projected'];
        $lv_proj_edate_projected = date('y-m-d', strtotime($lv_proj_edate));




        $lv_supervisor = $fp_arr_result['supervisor'];
        $lv_cust_name = $fp_arr_result['cust_name'];
        $lv_domain_id = $fp_arr_result['domain_id'];



        $lv_nedate = $fp_arr_result['new_edate'];
        $lv_new_edate = date('y-m-d', strtotime($lv_nedate));




        $lv_new_sup_corp_id = $fp_arr_result['new_sup_corp_id'];
        $lv_new_sup_id = $fp_arr_result['new_sup_id'];
        $lv_new_sup_name = $fp_arr_result['new_sup_name'];
        $lv_reason = $fp_arr_result['reason'];
        $lv_req_by = $fp_arr_result['req_by'];

        $lv_status = $fp_arr_result['status'];
        $lv_ops_comments = $fp_arr_result['ops_comments'];

        $sql = "INSERT INTO `rmg_tool`.`trans_ammendment` (`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`, `proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`)"
                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate', '$lv_new_sup_corp_id', $lv_new_sup_id, '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments')";



        $re_result = cl_DB::updateResultIntoTable($sql);
        if ($re_result) {
            if ($lv_new_edate == '') {
                // send mail for  change in supervisor;
            } else if ($lv_new_sup_corp_id == '') {
                // send mail for  change in enddate;
            } elseif (!$lv_new_edate == '' && !$lv_new_sup_corp_id == '') {
                // send mail for both change in supervisor and change in end  date
            }
        }
        return $re_result;
    }

}
