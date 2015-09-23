<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_Lock
 *
 * @author ptellis
 */
class cl_Lock {

    const C_ARR_SO_ID = 'so_id';
    const C_ARR_EMP_ID = 'emp_id';
    const C_ARR_STAT = 'status';
    const C_PROP_ID = 'prop_id';
    const C_TRANS_ID = 'trans_id';    
    const C_ARR_LINK = 'link';
// const C_PARENT_PROPOS_ID = 'propos_id';
// const C_REQUESTOR_ID = 'requestor_id';

    public function __construct() {
        
    }

    public function getSoftLockedEmps() {
        
    }

    public function getHardLockedEmps() {
        
    }

    public function getSoftLockExpiredEmps() {
        
    }
//Soft Lock
    public function setTransId() {        //find max trans_id
        $lv_sql = "select max(trans_id) from trans_locks";
        $lv_max_trans_id = cl_DB::getResultsFromQuery($lv_sql);
        return $lv_max_trans_id;
    }

    public function setSoftLock($lv_trans_id, $fp_v_so_id, $fp_v_emp_id, $lv_prop_id, $fp_v_requestor_id) {

        //To Retrieve lock Start & end date  
        $lv_start_date = date('y-m-d');
        $lv_end_date = date('y-m-d', strtotime('+2 days'));
        //Inserting Data into table
        $sql = "Insert Into trans_locks(trans_id,so_id,emp_id,status,parent_prop_id,"
                . "requestor_id,lock_start_date,lock_end_date) "
                . "Values('$lv_trans_id','$fp_v_so_id','$fp_v_emp_id','S121',"
                . "'$lv_prop_id','$fp_v_requestor_id','$lv_start_date','$lv_end_date')";

        $lv_result = cl_DB::postResultIntoTable($sql);

        return $lv_result;  //return true or false
    }
//Lock History
    public function setLockHistory($lv_trans_id, $lv_so_id, $lv_emp_id, $status, $lv_prop_id, $lv_request_id) {
        $lv_start_date = date('y-m-d');
        $lv_end_date = date('y-m-d', strtotime('+2 days'));

        $lv_sql = "Insert into trans_locks_history(trans_id,so_id,emp_id,status,parent_prop_id,"
                . "requestor_id,lock_start_date,lock_end_date) "
                . "VALUES($lv_trans_id,$lv_so_id,$lv_emp_id,'$status',$lv_prop_id,"
                . "$lv_request_id,'$lv_start_date','$lv_end_date')";

        $lv_result = cl_DB::postResultIntoTable($lv_sql);

        return $lv_result;
    }

    public function ApproveSoftLock($fp_arr_so, $fp_arr_emp, $fp_arr_stat, $lv_prop_id ,$fp_arr_link) {
       
        $lv_obj = new cl_DB();
        $lv_db = $lv_obj->getDBHandle();
        $softlock_count = 0;
        $lv_count = count($fp_arr_so);
        for ($i = 0; $i < $lv_count; $i++) {
           
            if ($fp_arr_stat[$i] == 'SoftLocked') {
                $lv_request_id = 444; //now passing Default request id but need to fetch dynamically
                $lv_value = self::setTransId(); // find max trans_id

                foreach ($lv_value as $key => $value) { //generate new trans_id
                    $lv_trans_id = $value['max(trans_id)'];
                }
                $lv_trans_id++; //newly generated trans id


                try {
                    mysqli_begin_transaction($lv_db);
                    $lv_app_result = self::setSoftlock($lv_trans_id, $fp_arr_so[$i], $fp_arr_emp[$i], $lv_prop_id, $lv_request_id);
                    $lv_history = self::setLockHistory($lv_trans_id, $fp_arr_so[$i], $fp_arr_emp[$i], 'S121', $lv_prop_id, $lv_request_id);
                    mysqli_commit($lv_db);
                } catch (Exception $ex) {
                    mysqli_rollback($lv_db);
                    echo 'Failed-' . $ex->getMessage();
                }
                   
                if ($lv_app_result == TRUE && $lv_history == TRUE) {
                    $softlock_count++;      //counting no of soft lock
                    
                    
                    $lv_link = self::getLink($fp_arr_so[$i], $fp_arr_emp[$i]);
                    
                    
                     // call method to send mail 
                    $i_mode = 'SL';
                    $lo_mail_noti = new cl_NotificationMails();
                    $lo_mail_noti->sendnotification($fp_arr_so[$i], $i_mode,$lv_link ,$lv_trans_id,$fp_arr_emp[$i]);
                    // call method to send mail 
                    
                    
                    
                }
            } elseif ($fp_arr_stat[$i] == 'Rejected') {
                $lv_result = self::rejectProposal($lv_prop_id, $fp_arr_emp[$i], $fp_arr_so[$i]);
            }
        }
        return $softlock_count; //returns number of employess soft locked
    }
//HardLock
    public function setHardLock($fp_v_lock_trans_id,$fp_sdate,$fp_edate) {
        
        $sql1 = "UPDATE trans_locks SET status='S201',lock_start_date ='$fp_sdate',lock_end_date = '$fp_edate' WHERE trans_id = $fp_v_lock_trans_id";
        $re_sos = cl_DB::updateResultIntoTable($sql1);
        print_r($re_sos);
    }
public function ApproveHardLock($fp_v_lock_trans_id) {
   
      
        $lv_obj = new cl_DB();
        $lv_db = $lv_obj->getDBHandle();
        $lv_trans_result = self::getTransDetails($fp_v_lock_trans_id);
        foreach ($lv_trans_result as $key => $value) {
            $lv_trans_id = $value['trans_id'];
            $lv_so_id = $value['so_id'];
            $lv_emp_id = $value['emp_id'];
            $lv_status = $value['status'];
            $lv_prop_id = $value['parent_prop_id'];
            $lv_req_id = $value['requestor_id'];
//            $lv_start_date = $value['lock_start_date'];
//            $lv_end_date = $value['lock_end_date'];
//            $lv_up_by = $value['updated_by'];
        }
     
        $fp_sdate = self::getsostartdate($lv_so_id);
       
        $fp_edate = self::getsoenddate($lv_so_id);
     
        
        if($lv_status == 'S121')
            {
        try {
            mysqli_begin_transaction($lv_db);
            $lv_set_hardlock = self::setHardLock($lv_trans_id,$fp_sdate,$fp_edate);
            $lv_history = self::setLockHistory($lv_trans_id, $lv_so_id, $lv_emp_id, 'S201', $lv_prop_id, $lv_req_id);
            mysqli_commit($lv_db);
        } catch (Exception $ex) {
            mysqli_rollback($lv_db);
            echo 'Failed-' . $ex->getMessage();
        }
        }
        else
        {
            echo "Slock expired";
        }
    }
    public function rejectProposal($fp_v_proposal_id, $fp_v_emp_id, $fp_v_so_id) {
        $lv_query = "update trans_proposals SET rejected = 'X' where prop_id ='$fp_v_proposal_id'
and emp_id ='$fp_v_emp_id'
and so_id ='$fp_v_so_id'";

        $re_sos = cl_DB::updateResultIntoTable($lv_query);

        if ($re_sos == true) {
            $lv_str_success = "Record updated successfully";
            return $lv_str_success;
        } else {
            $lv_str_fail = "Error: " . $sql . "<br>" . $conn->error;
            return $lv_str_fail;
        }


//   $lv_query = "update trans_proposals SET rejected = 'X' where prop_id ='$fp_v_proposal_id'
//and emp_id ='$fp_v_emp_id'
//and so_id ='$fp_v_so_id'";
//   
//    $re_sos = cl_DB::updateResultIntoTable($lv_query);
//       
//                 if ($re_sos == true)
//                       {
//                         $lv_str_success = "Record updated successfully";
//    		         return $lv_str_success;
//                       }
//
//                 else
//                      {
//                        $lv_str_fail = "Error: " . $sql . "<br>" . $conn->error;
//    		       return $lv_str_fail ;
//		      }
    }

//Click here to reject
//htttp://rmt/api/vi/accept_SL/?trans_id=001;
    public function rejectSoftLock($fp_v_lock_trans_id) {

        $sql = "UPDATE trans_locks SET status='S221' WHERE trans_id = $fp_v_lock_trans_id";
        $lv_obj = new cl_DB();
        $lv_db = $lv_obj->getDBHandle();
        $lv_trans_result = self::getTransDetails($fp_v_lock_trans_id);
        foreach ($lv_trans_result as $key => $value) {
            $lv_trans_id = $value['trans_id'];
            $lv_so_id = $value['so_id'];
            $lv_emp_id = $value['emp_id'];
               $lv_status = $value['status'];
            $lv_prop_id = $value['parent_prop_id'];
            $lv_req_id = $value['requestor_id'];
//            $lv_start_date = $value['lock_start_date'];
//            $lv_end_date = $value['lock_end_date'];
//            $lv_up_by = $value['updated_by'];
        }
        if($lv_status=='S121'){
        try {
            mysqli_begin_transaction($lv_db);
            $re_sos = cl_DB::updateResultIntoTable($sql);
            $lv_history = self::setLockHistory($lv_trans_id, $lv_so_id, $lv_emp_id, 'S221', $lv_prop_id, $lv_req_id);
            mysqli_commit($lv_db);
        } catch (Exception $ex) {
            mysqli_rollback($lv_db);
            echo 'Failed-' . $ex->getMessage();
        }
        }
        else
        {
            echo 'Slock expired';
        }
    }
    

    public function getTransDetails($fp_v_trans_id) {
        $sql = "SELECT * FROM `trans_locks` WHERE trans_id = $fp_v_trans_id";
        $lv_result = cl_DB:: getResultsFromQuery($sql);
        return $lv_result;
        
    }
    public function getsostartdate($fp_v_so_id)
    {
        $sql = "SELECT so_sdate FROM `v_open_so` WHERE so_no = $fp_v_so_id";
        $lv_result = cl_DB:: getResultsFromQuery($sql);
        foreach ($lv_result as $key => $value)
        {
            $so_sdate = $value['so_sdate'];
        }
        
        
        return $so_sdate;
    }
    public function getsoenddate($fp_v_so_no)
    {
        
        $sql = "SELECT so_endate FROM `v_open_so` WHERE so_no = $fp_v_so_id";
        $lv_result = cl_DB:: getResultsFromQuery($sql);
        foreach ($lv_result as $key => $value)
        {
            $so_endate = $value['so_endate'];
        }
        
        
        return $so_endate;
    }
    
    public static function getLink($fp_v_so_no,$fp_v_emp_id)
    {
        $lt_Sodetails = getDetails::getSODetails($fp_v_so_no);
        $lt_empdetails = getDetails::getEmpDetails($fp_v_emp_id);
        
        foreach ($lt_Sodetails as $key => $value) {
            $lv_proj_code = $value['so_proj_id'];
            $lv_proj_name = $value['so_proj_name'];
            $lv_so_no = $value['so_no'] ;
            $lv_sdate = $value['so_sdate'];
            $lv_edate = $value['so_endate'];
        }
        
        foreach($lt_empdetails as $key => $value)
        {
            $lv_bu = $value['idp'];
            $lv_sub_bu = $value['sub_bu'];
            $lv_svc_line = $value['svc_line'];
            $lv_loc = $value['org'];
            $lv_emp_id = $value['emp_id'];
            $lv_emp_name = $value['emp_name'];
            $lv_prime_skill = $value['skill1_l4'];
            $lv_lvl = $value['level'];
        }
        
        $lv_link = "http://localhost/rmt1/UI/buttons_rmt/WebContent/approval.html/?bu=$lv_bu&subbu=$lv_sub_bu&svcline=$lv_svc_line&loc=$lv_loc&emp_id=$lv_emp_id&emp_name=$lv_emp_name&lv_prime_skill=$lv_prime_skill&lvl=$lv_lvl&proj_code=$lv_proj_code&proj_name=$lv_proj_name&so_no=$lv_so_no&sdate=$lv_sdate&edate=$lv_edate";
        return $lv_link;
        
    }
    
   
}
