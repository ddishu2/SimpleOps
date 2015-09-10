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
// const C_PARENT_PROPOS_ID = 'propos_id';
// const C_REQUESTOR_ID = 'requestor_id';

public function __construct(){}
public function getSoftLockedEmps()
{
    
}
public function getHardLockedEmps(){}
public function getSoftLockExpiredEmps()
{
    
}
public function setTransId()        //find max trans_id
{
    $lv_sql = "select max(trans_id) from trans_locks";
    $lv_count = cl_DB::getResultsFromQuery($lv_sql);       
    return $lv_count;
  
}  

public function setSoftLock($fp_v_so_id,$fp_v_emp_id,$fp_v_requestor_id)
{
    //To Generate Trans ID 
    $lv_value =     self::setTransId();
    foreach($lv_value as $key => $value)
    {
        $lv_trans_id = $value['max(trans_id)'];
    }
    $lv_trans_id++;
    //To Retrieve lock Start & end date  
    $lv_start_date = date('y-m-d');
    $lv_end_date = date('y-m-d',strtotime('+2 days'));
    //Inserting Data into table
    $sql = "Insert Into trans_locks(trans_id,so_id,emp_id,status,"
          . "requestor_id,lock_start_date,lock_end_date) "
          . "Values('$lv_trans_id','$fp_v_so_id','$fp_v_emp_id','S121',"
          . "'$fp_v_requestor_id','$lv_start_date','$lv_end_date')"; 
    
    $lv_result = cl_DB::postResultIntoTable($sql);

    return $lv_result;    
}

public function ApproveSoftLock($fp_arr_so,$fp_arr_emp,$fp_arr_stat,$lv_prop_id)
{
      $count = 0;
    $lv_count = count($fp_arr_so);
     for($i = 0 ; $i< $lv_count ; $i++){
         
         if($fp_arr_stat[$i] == 'SoftLocked'){
     $lv_request_id = 444;//now passing Default request id but need to fetch dynamically 
        $lv_result = self::setSoftlock($fp_arr_so[$i],$fp_arr_emp[$i],$lv_request_id);                       
        if($lv_result == true)
        {
            $count++;        
        }
         }
         elseif($fp_arr_stat[$i]== 'Rejected')
         {
              $lv_result = self::rejectProposal($lv_prop_id,$fp_arr_emp[$i],$fp_arr_so[$i]); 
         }
     }
      return $count; //returns number of employess soft locked
}

public function setHardLock($fp_v_lock_trans_id)
{
 $sql1 = "UPDATE trans_locks SET status='S201' WHERE trans_id = $fp_v_lock_trans_id";
  $re_sos = cl_DB::updateResultIntoTable($sql1);
}
public function rejectProposal($fp_v_proposal_id,$fp_v_emp_id,$fp_v_so_id)
{
   $lv_query = "update trans_proposals SET rejected = 'X' where prop_id ='$fp_v_proposal_id'
and emp_id ='$fp_v_emp_id'
and so_id ='$fp_v_so_id'";
   
    $re_sos = cl_DB::updateResultIntoTable($lv_query);
       
                 if ($re_sos == true)
                       {
                         $lv_str_success = "Record updated successfully";
    		         return $lv_str_success;
                       }

                 else
                      {
                        $lv_str_fail = "Error: " . $sql . "<br>" . $conn->error;
    		       return $lv_str_fail ;
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
public function rejectSoftLock($fp_v_lock_trans_id)
{   
    $sql = "UPDATE trans_locks SET status='S221' WHERE trans_id = $fp_v_lock_trans_id";
    $re_sos = cl_DB::updateResultIntoTable($sql);
}
       
}
