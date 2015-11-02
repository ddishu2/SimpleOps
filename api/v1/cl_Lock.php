<?php
/**
 * Description of cl_Lock
 *
 * @author ptellis
 */

require_once 'cl_abs_QueryBuilder.php';
require_once 'cl_DB.php';
class cl_Lock {

    const C_TABLE_NAME = 'trans_locks';
    const C_STATUS_SL = 'S';
    const C_ARR_SO_ID = 'so_id';
    const C_ARR_EMP_ID = 'emp_id';
    const C_ARR_STAT = 'status';
    const C_PROP_ID = 'prop_id';
    const C_TRANS_ID = 'trans_id';    
    const C_ARR_LINK = 'link';
    const C_COMMENTS = 'comments';
    const C_FNAME_STATUS = 'status';
    const C_FNAME_LOCK_END_DATE = 'lock_end_date';
    
    const C_STATUS_HARD_LOCK = 'S201';
//    const C_STATUS_HARD_LOCK_EXPIRED = ;
    const C_STATUS_SOFT_LOCK = 'S121';
    const C_STATUS_SOFT_LOCK_REJECTED = 'S221';
    const C_STATUS_SOFT_LOCK_EXPIRED = 'S300';
    
    private static $arr_SO_that_rejectedemps = [];
// const C_PARENT_PROPOS_ID = 'propos_id';
// const C_REQUESTOR_ID = 'requestor_id';

    public function __construct() {
        
    }

    public function getSoftLockedEmps() {
        
    }

    public function getHardLockedEmps($fp_v_start_date = null, $fp_v_end_date =  null) 
    {
        $lv_query = cl_abs_QueryBuilder::C_SQL_SELECT
                   .cl_abs_QueryBuilder::C_SQL_ALL
                   .cl_abs_QueryBuilder::C_SQL_FROM
                   .self::C_TABLE_NAME.PHP_EOL
                   .cl_abs_QueryBuilder::C_SQL_WHERE
                   .self::C_FNAME_STATUS.cl_abs_QueryBuilder::C_SQL_EQUALS.self::C_STATUS_SOFT_LOCK_EXPIRED
                   .cl_abs_QueryBuilder;
    }

    public function getSoftLockExpiredEmps() {
        $lv_query = cl_abs_QueryBuilder::C_SQL_SELECT
                   .cl_abs_QueryBuilder::C_SQL_ALL
                   .cl_abs_QueryBuilder::C_SQL_FROM
                   .self::C_TABLE_NAME.PHP_EOL
                   .cl_abs_QueryBuilder::C_SQL_WHERE
                   .self::C_FNAME_STATUS.cl_abs_QueryBuilder::C_SQL_EQUALS.self::C_STATUS_SOFT_LOCK_EXPIRED;
        
    }
//Soft Lock
    public function setTransId() {        //find max trans_id
//        $lv_sql = 'SELECT'.PHP_EOL
//                 .'MAX(trans_id)'.PHP_EOL
//                . 'FROM '.self::C_TABLE_NAME.PHP_EOL;
//        $lv_max_trans_id = cl_DB::getResultsFromQuery($lv_sql);
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
    
//    f
//    rmt/api/v1/processLock/?lock_id=123&action='approve'&comments='Approved'
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
    

    public function ApproveSoftLock($fp_arr_so, $fp_arr_emp, $fp_arr_stat, $lv_prop_id ) {
       
        $lv_obj = new cl_DB();
        $lv_db = $lv_obj->getDBHandle();
        $softlock_count = 0;
        $rejected_proposal_count = 0;
        $res_count =[];
        $lv_count = count($fp_arr_so);
        for ($i = 0; $i < $lv_count; $i++) {
           
            if ($fp_arr_stat[$i] == 'Approve') {
                $lv_request_id = 444; //now passing Default request id but need to fetch dynamically
                $lv_value = self::setTransId(); // find max trans_id

                foreach ($lv_value as $key => $value) { //generate new trans_id
                    $lv_trans_id = $value['max(trans_id)'];
                }
                $lv_trans_id++; //newly generated trans id


                try {
                    
                    mysqli_autocommit( $lv_db,false);
                    mysqli_begin_transaction($lv_db);
                        $lv_history = self::setLockHistory($lv_trans_id, $fp_arr_so[$i], $fp_arr_emp[$i], 'S121', $lv_prop_id, $lv_request_id);
                        
                        $lv_app_result = self::setSoftlock($lv_trans_id, $fp_arr_so[$i], $fp_arr_emp[$i], $lv_prop_id, $lv_request_id);
                    mysqli_commit($lv_db);
                    echo 'History'.$lv_history;
                } catch (Exception $ex) {
                    mysqli_rollback($lv_db);
                    echo 'Failed-' . $ex->getMessage();
                }
                   
                if ($lv_app_result == TRUE && $lv_history == TRUE) {
                    $softlock_count++;      //counting no of soft lock
                    
                    
                    $lv_link = self::getLink($fp_arr_so[$i], $fp_arr_emp[$i],$lv_trans_id);
                    
                    
                     // call method to send mail 
                    //$i_mode = 'SL';
                    $lo_mail_noti = new cl_NotificationMails();
                    //$lo_mail_noti->sendnotification($fp_arr_so[$i], $i_mode,$lv_link ,$lv_trans_id,$fp_arr_emp[$i]);
                   
                    $lo_mail_noti->sendSoftLockNotification($fp_arr_so[$i],$lv_link,$fp_arr_emp[$i],$lv_trans_id);
                    
                    
                }
            } elseif ($fp_arr_stat[$i] == 'Reject') {
                $lv_result = self::rejectProposal($lv_prop_id, $fp_arr_emp[$i], $fp_arr_so[$i]);
                $rejected_proposal_count++;
                
            }
        }
        $res_count['softlocked'] = $softlock_count;
        $res_count['rejected'] = $rejected_proposal_count;
        
        return $res_count; //returns number of employess soft locked and rejected proposals
    }
//HardLock
    public function setHardLock($fp_v_lock_trans_id,$fp_sdate,$fp_edate) {
        
        $sql1 = "UPDATE trans_locks SET status='S201',lock_start_date ='$fp_sdate',lock_end_date = '$fp_edate' WHERE trans_id = $fp_v_lock_trans_id";
        $re_sos = cl_DB::updateResultIntoTable($sql1);
        //print_r($re_sos);
    }
    public function setComments($lv_trans_id, $fp_v_status,$fp_v_comments)
    {
        
        //$sql1 ="INSERT INTO `rmg_tool`.`trans_comment` (`trans_id`, `status`, `comment`) VALUES ($lv_trans_id, $fp_v_status, $fp_v_comments);";
//        $sql = "insert into trans_commit('trans_id', 'status', 'comment') values($lv_trans_id, $fp_v_status,$fp_v_comments)";
        $sql = "INSERT INTO `rmg_tool`.`trans_comment` (`trans_id`, `status`, `comment`) VALUES($lv_trans_id,'$fp_v_status','$fp_v_comments')";
        $lv_result = cl_DB::updateResultIntoTable($sql);
        
        
    }
    public function setRejectionCount($lv_so_no)
    {
        echo "chk point 1" ;
        $lv_result = false;
        $select = "SELECT `so_no` FROM `trans_count`";
        self::$arr_SO_that_rejectedemps = cl_DB::getResultsFromQuery($select);
//        print_r(self::$arr_SO_that_rejectedemps);
        foreach (self::$arr_SO_that_rejectedemps as $key => $value){
           
         if (in_array($lv_so_no, $value) == true) {
              echo "chk point 2 here" ;
             $update = "UPDATE `trans_count` SET `rej_count`= rej_count + 1 WHERE so_no = $lv_so_no";
             $lv_result = cl_DB::updateResultIntoTable($update);
             return $lv_result;
         }
        }
         
              echo "chk point 2 there" ;
        $insert= "INSERT INTO `rmg_tool`.`trans_count` (`so_no`, `rej_count`) VALUES ($lv_so_no, 1)";
        $lv_result = cl_DB::updateResultIntoTable($insert);
         
        
        return $lv_result;
    }
    public static function getRejectionCount($lv_so_id)
    {
//        $count = 0;
//        $select = "SELECT  `rej_count` FROM `trans_count` WHERE so_no = $lv_so_id";
//        $result = cl_DB::getResultsFromQuery($select);
//        foreach($result as $key => $value)
//        {
//            $count = $value['rej_count'];
//        }
//        return $count;
        
        $count = 0;
        $select = "SELECT * FROM `trans_count`";
        $result = cl_DB::getResultsFromQuery($select);
         
        foreach($result as $key => $value)
        {
            if (in_array($lv_so_id, $value))
            {
            $count = $value['rej_count'];
            }
        } 
        
        
        return $count;
        
        
        
        
    }
    /*hardlocks an employee  
      returns 1 if successfull
     returns -1 if failed     */
public function ApproveHardLock($fp_v_lock_trans_id,$fp_v_comments) {
   
      
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
          
            $lv_comments = self::setComments($lv_trans_id,'S201',$fp_v_comments);
            
            mysqli_commit($lv_db);
            return 1;
        } catch (Exception $ex) {
            mysqli_rollback($lv_db);
            echo 'Failed-' . $ex->getMessage();
            return -1;
        }
        }
        else
        {
            //echo "Slock expired";
              return -1;            
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
    
    /* rejects a hard lock 
      returns 1 if successfull 
     *returns  -1 if unsuccessful */
    
    public function rejectSoftLock($fp_v_lock_trans_id,$fp_v_comments) {

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
            $lv_comments = self::setComments($lv_trans_id,'S221',$fp_v_comments);
            
            $lv_result = self::setRejectionCount($lv_so_id);
            $lv_Rej_count = self ::getRejectionCount($lv_so_id);
            if($lv_Rej_count >= 3)
            {
                //method to send mail to so_owner to clsoe the SO
            }
            
            
            mysqli_commit($lv_db);
            return 1;
        } catch (Exception $ex) {
            mysqli_rollback($lv_db);
           // echo 'Failed-' . $ex->getMessage();
            return -1;
        }
        }
        else
        {
           // echo 'Slock expired';
            return -1;
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
        
        $sql = "SELECT so_endate FROM `v_open_so` WHERE so_no = $fp_v_so_no";
        $lv_result = cl_DB:: getResultsFromQuery($sql);
        foreach ($lv_result as $key => $value)
        {
            $so_endate = $value['so_endate'];
        }
        
        
        return $so_endate;
    }
    /*
     creates a link for popup on email 
     */
    public static function getLink($fp_v_so_no,$fp_v_emp_id,$fp_v_trans_id)
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
        
        //$lv_link = "http://localhost/rmt1/UI/buttons_rmt/WebContent/approve.php/?bu=$lv_bu&subbu=$lv_sub_bu&svcline=$lv_svc_line&loc=$lv_loc&emp_id=$lv_emp_id&emp_name=$lv_emp_name&lv_prime_skill=$lv_prime_skill&lvl=$lv_lvl&proj_code=$lv_proj_code&proj_name=$lv_proj_name&so_no=$lv_so_no&sdate=$lv_sdate&edate=$lv_edate";
      
        $lv_link = "http://localhost/rmt/UI/buttons_rmt/WebContent/approve.php/?bu=$lv_bu&subbu=$lv_sub_bu&svcline=$lv_svc_line&loc=$lv_loc&emp_id=$lv_emp_id&emp_name=$lv_emp_name&lv_prime_skill=$lv_prime_skill&lvl=$lv_lvl&proj_code=$lv_proj_code&proj_name=$lv_proj_name&so_no=$lv_so_no&sdate=$lv_sdate&edate=$lv_edate&trans_id=$fp_v_trans_id";
        return $lv_link;
        
    }
    
   public function getSoftLocked($fp_v_start_date, $fp_v_end_date)
   {
       $lv_query = 'SELECT '.PHP_EOL
                   . '* '.PHP_EOL
                   . 'FROM'.PHP_EOL
                   . self::C_TABLE_NAME.PHP_EOL
                   . 'WHERE'.PHP_EOL
                   . self::C_FNAME_STATUS.' = '.self::C_STATUS_SOFT_LOCK.PHP_EOL
                   . 'AND'.PHP_EOL 
                   . self::C_FNAME_LOCK_END_DATE.PHP_EOL
                   .'BETWEEN '.PHP_EOL
                   . 'CAST('."'$fp_v_start_date'".') AS DATE'.PHP_EOL
                   . 'AND CAST('."'$fp_v_end_date'".') AS DATE'.PHP_EOL;
       $lo_DB   = new cl_DB();
       $re_data = $lo_DB->getResultsFromQuery($lv_query);
       return $re_data;
   }
   
   public function getHardLocked($fp_v_start_date, $fp_v_end_date)
   {
       
   }
   
   public function getSoftLockReleased($fp_v_start_date, $fp_v_end_date)
   {
       
   }
   
   public function getHardLockReleased($fp_v_start_date, $fp_v_end_date)
   {
       
   }
   
   private function getLockData(array $fp_arr_statuses, $fp_v_start_date, $fp_v_end_date)
   {
       $lv_startDate = $fp_v_start_date;
       $lv_endDate   = $fp_v_end_date;
       /**
        * If dates are invalid, default start and end dates to today's date
        */
       if(cl_abs_QueryBuilder::isDateRangeValid($fp_v_start_date, $fp_v_end_date)=== false)
       {
           $lv_startDate = date(cl_abs_QueryBuilder::C_DATE_FORMAT);
           $lv_endDate   = $lv_startDate;
       }
       $lv_startDate = cl_abs_QueryBuilder::getSQLDateFromString($lv_startDate);
       $lv_endDate   = cl_abs_QueryBuilder::getSQLDateFromString($lv_endDate);
        
       $lv_statusClause    = cl_abs_QueryBuilder::getInQuery(self::C_FNAME_STATUS, $fp_arr_statuses);
       $lv_end_date_clause = cl_abs_QueryBuilder::getBetweenFilterQuery
                            (self::C_FNAME_LOCK_END_DATE,$lv_startDate,$lv_endDate );        
       $lv_query = 'SELECT'.PHP_EOL
                    .'*'   .PHP_EOL
                   .'FROM' .PHP_EOL
                   .self::C_TABLE_NAME.PHP_EOL
                   .'WHERE'.PHP_EOL
                   .    $lv_statusClause.PHP_EOL
                   .    cl_abs_QueryBuilder::C_SQL_AND.PHP_EOL
                   .    $lv_end_date_clause.PHP_EOL;
       $lo_DB = new cl_DB(); 
       $re_data = $lo_DB->getResultsFromQuery($lv_query);
       return $re_data;
   }    
   
   
}
