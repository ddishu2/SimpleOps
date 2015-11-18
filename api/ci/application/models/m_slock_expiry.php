<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'cl_DB.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'cl_NotificationMails.php';
// $lv_query = "SELECT * FROM trans_locks where status = 'S121' and current_date > lock_end_date";
// $lt_data = cl_DB::getResultsFromQuery($lv_query);
// constants
const c_trans_locks =  'trans_locks';
$status = $value['S121'];
$lock_end_date = $value['lock_end_date'];
//$this->db->select('*');
$this->db->from(self::c_trans_locks. 'AS trans_locks');
$this->db->where('status =', $status);
$this->db->and_where('current_date >',$lock_end_date);

//        print_r($lt_data);
$lv_obj = new cl_DB();
$lv_db = $lv_obj->getDBHandle();
//        print_r($lt_data);
foreach ($lt_data as $key => $value) {
//            print_r($value);
    $lv_trans_id = $value['trans_id'];
    $lv_so_id = $value['so_id'];
    $lv_emp_id = $value['emp_id'];
    $parent_prop_id = $value['parent_prop_id'];
//    echo 'prop-id' . $parent_prop_id;
    $parent_prop_item = $value['parent_prop_item'];
    $requestor_id = $value['requestor_id'];
    $lock_start_date = $value['lock_start_date'];
//    echo $lock_start_date;
    $lock_end_date = $value['lock_end_date'];
    $updated_by = $value['updated_by'];
    $updated_on = $value['updated_on'];
//    echo $lv_trans_id;
    try {
        mysqli_begin_transaction($lv_db);
        //$queryUpdate = "update trans_locks set status ='S300' where trans_id =  $lv_trans_id ";
        //$lv_app_result = cl_DB::updateResultIntoTable($queryUpdate);
        $this->db->update(self::c_trans_locks. 'AS trans_locks'); 
        $this->db->where('trans_id', $lv_trans_id);
        
    
        //echo "res2 " . $res2;
//                     $queryInsert = "insert into trans_locks_history(trans_id,so_id,emp_id,status,parent_prop_id,parent_prop_item,requestor_id,lock_start_date,lock_end_date,updated_by,dpdated_on)values($lv_trans_id,$lv_so_id,$lv_emp_id,'S300',$parent_prop_id,$parent_prop_item,$requestor_id,$lock_start_date,$lock_end_date,$updated_by,$updated_on)";
//                     $res1 = cl_DB::postresultintotable($queryInsert);
//                     $queryinsert = "INSERT INTO `trans_locks_history`(`trans_id`, `so_id`, `emp_id`, `status`, `parent_prop_id`, `parent_prop_item`, `requestor_id`, `lock_start_date`, "
//                             . "`lock_end_date`, `updated_by`, `updated_on`) "
//                             . "VALUES ($lv_trans_id,$lv_so_id,$lv_emp_id,'S300',$parent_prop_id,$parent_prop_item,$requestor_id,$lock_start_date,"
//                             . "$lock_end_date,$updated_by,$updated_on)";
        $lv_history = "Insert into trans_locks_history(trans_id,so_id,emp_id,status,parent_prop_id,parent_prop_item,requestor_id,lock_start_date,lock_end_date) "
                . "values($lv_trans_id,$lv_so_id,$lv_emp_id,'S300',$parent_prop_id,$parent_prop_item,$requestor_id,'$lock_start_date','$lock_end_date')";
        //$result1 = cl_DB:: postResultIntoTable($lv_history);
        $this->db->query($lv_history);

//        echo $result1;
        mysqli_commit($lv_db);
    } catch (Exception $ex) {
        mysqli_rollback($lv_db);
        echo 'Failed-' . $ex->getMessage();
    }
      if ($lv_app_result == TRUE && $lv_history == TRUE){
                    
                     // call method to send mail 
                   
                    $lo_mail_noti = new cl_NotificationMails();
                    //$lo_mail_noti->sendnotification($lv_so_id, $i_mode,$lv_link ,$lv_trans_id,$lv_emp_id);
               
                    $lo_mail_noti->sendSoftLockReleaseNotification($lv_so_id, $lv_emp_id, $lv_trans_id);
                    
                    
                }
}    