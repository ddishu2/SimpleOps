<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//require __DIR__ . DIRECTORY_SEPARATOR . 'cl_DB.php';
//require __DIR__ . DIRECTORY_SEPARATOR . 'cl_NotificationMails.php';
// $lv_query = "SELECT * FROM trans_locks where status = 'S121' and current_date > lock_end_date";
// $lt_data = cl_DB::getResultsFromQuery($lv_query);
// constants

/**
 * Description of l_slock_expiry
 *
 * @author vkhisty
 */
require_once(APPPATH.'models/m_Notifications.php');
class l_slock_expiry 

{
const c_trans_locks =  'trans_locks';
const C_softlock_status = 'S121';
const C_slock_expiry_status = 'S300';
const c_star = '*';
const c_status = 'status';
const C_trans_lock_history = 'trans_locks_history';

 public static function slock_expiry_details($fp_so_id)
{

$status = self::C_softlock_status;

$ci_ins =& get_instance();
$lv_count = count($fp_so_id);
for($i = 0 ;$i<$lv_count; $i++ ){   
$ci_ins->db->select('*');
$ci_ins->db->from(self::c_trans_locks);
$ci_ins->db->where(self::c_status, $status); 
$query = $ci_ins->db->get(); 
$lt_data = $query->result_array();
print_r($lt_data);
}

foreach ($lt_data as $key => $value) {

    $lv_trans_id = $value['trans_id'];
    $lv_so_id = $value['so_id'];
    $lv_emp_id = $value['emp_id'];
    $parent_prop_id = $value['parent_prop_id'];
    $parent_prop_item = $value['parent_prop_item'];
    $requestor_id = $value['requestor_id'];
    $lock_start_date = $value['lock_start_date'];
    $lock_end_date = $value['lock_end_date'];
    
    $updated_by = $value['updated_by'];
    $updated_on = $value['updated_on'];
    try {
        $lv_status = self::C_slock_expiry_status;
        
        $upd_data2 = array('status' => $lv_status,); 
           $ci_ins->db->where('trans_id', $lv_trans_id);
        $lv_app_result = $ci_ins->db->update(self::c_trans_locks, $upd_data2);
        
        $data = array('trans_id' => $lv_trans_id,
                         'so_id' =>  $lv_so_id,
                         'emp_id' =>$lv_emp_id,
                         'status' => $lv_status,
                         'parent_prop_id' => $parent_prop_id,
                         'parent_prop_item' =>$parent_prop_item,
                         'requestor_id' => $requestor_id,
                         'lock_start_date' => $lock_start_date,
                         'lock_end_date' => $lock_end_date,
                         'updated_by' => $updated_by,
                         'updated_on' => $updated_on,
                         
                   );
           $lv_history =  $ci_ins->db->insert(self::C_trans_lock_history, $data);
        
    } catch (Exception $ex) {
        echo 'Failed-' . $ex->getMessage();
    }
      if ($lv_app_result == TRUE && $lv_history == TRUE){
                    
                      
       $lo_mail_noti = new m_Notifications();
                    //$lo_mail_noti->sendnotification($lv_so_id, $i_mode,$lv_link ,$lv_trans_id,$lv_emp_id);
               
      echo($lo_mail_noti->sendSoftLockReleaseNotification($lv_so_id, $lv_emp_id, $lv_trans_id));
                  
                    
                }
} 
}
}