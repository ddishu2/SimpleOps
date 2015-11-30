<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(APPPATH.'models/getDetails.php');
class m_lock extends ci_model
{ 
   
//    require_once(APPPATH.'models/getDetails.php');
    public function __construct()
        {
               
               $this->load->database();
               $this->load->model('m_Notifications');

        }
     const C_SMART_PROJECT_CODE ='smart_proj_code';
    const C_FTE = 'FTE';
    const C_TAG_TYPE = 'tag_type';   
     const C_DATE_FORMAT    = 'Y-m-d';
     const C_FNAME_REJ_COUNT = 'rej_count';   
     const C_TABNAME_COUNT = 'trans_count';   
     const C_TABNAME = 'trans_locks';
     const C_TABNAMEHISTORY = 'trans_locks_history';
     const C_TABNAMECOMMENTS = 'trans_comment';
     const C_FNAME_COMMENTS = 'comment';
     const C_COMMENT_STATUS = 'status';
     const C_SO_NO = 'so_no';
     
     
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
    const C_FNAME_LOCK_START_DATE = 'lock_start_date';
    const C_FNAME_PROP_ID = 'parent_prop_id';
    
    
    
    const C_STATUS_HARD_LOCK = 'S201';
//    const C_STATUS_HARD_LOCK_EXPIRED = ;
    const C_STATUS_SOFT_LOCK = 'S121';
    const C_STATUS_SOFT_LOCK_REJECTED = 'S221';
    const C_STATUS_SOFT_LOCK_EXPIRED = 'S300';
    const C_STATUS_SOFT_LOCK_EXPIRED_DUE_TO_OTHERS_APPROVAL = 'S301';
    
    
    
    const C_REQUESTOR_ID = 'requestor_id';
    const C_MULTI = 'allow_multi';
    const C_FNAME_ALLOW_MULTI = 'allow_multi';
    
    const C_star ='*';
    const C_V_hard_lock = 'v_hard_lock';
    const C_curr_start_date = 'curr_start_date';
    const C_curr_end_date = 'curr_end_date';
    
    private static $arr_SO_that_rejectedemps = [];
//    private static $arr_result = [];
    
// const C_PARENT_PROPOS_ID = 'propos_id';
// const C_REQUESTOR_ID = 'requestor_id';

    

    public function getSoftLockedEmps() {
        
    }

//    public function getHardLockedEmps($fp_v_start_date = null, $fp_v_end_date =  null) 
//    {
//        $lv_query = cl_abs_QueryBuilder::C_SQL_SELECT
//                   .cl_abs_QueryBuilder::C_SQL_ALL
//                   .cl_abs_QueryBuilder::C_SQL_FROM
//                   .self::C_TABLE_NAME.PHP_EOL
//                   .cl_abs_QueryBuilder::C_SQL_WHERE
//                   .self::C_FNAME_STATUS.cl_abs_QueryBuilder::C_SQL_EQUALS.self::C_STATUS_SOFT_LOCK_EXPIRED
//                   .cl_abs_QueryBuilder;
//    }
//
//    public function getSoftLockExpiredEmps() {
//        $lv_query = cl_abs_QueryBuilder::C_SQL_SELECT
//                   .cl_abs_QueryBuilder::C_SQL_ALL
//                   .cl_abs_QueryBuilder::C_SQL_FROM
//                   .self::C_TABLE_NAME.PHP_EOL
//                   .cl_abs_QueryBuilder::C_SQL_WHERE
//                   .self::C_FNAME_STATUS.cl_abs_QueryBuilder::C_SQL_EQUALS.self::C_STATUS_SOFT_LOCK_EXPIRED;
//        
//    }
//Soft Lock
    public function setTransId() {        //find max trans_id
//        $lv_sql = 'SELECT'.PHP_EOL
//                 .'MAX(trans_id)'.PHP_EOL
//                . 'FROM '.self::C_TABLE_NAME.PHP_EOL;
//        $lv_max_trans_id = cl_DB::getResultsFromQuery($lv_sql);
          $this->db->select_max(self::C_TRANS_ID);
            $result = $this->db->get(self::C_TABNAME)->row();  
            $lv_max_trans_id = $result->trans_id;
        return $lv_max_trans_id;
    }
    
    public function setSoftLock($lv_trans_id, $fp_v_so_id, $fp_v_emp_id, $lv_prop_id, $fp_v_requestor_id,$fp_v_Multi) {

        //To Retrieve lock Start & end date  
        $lv_start_date = date('y-m-d');
        $lv_end_date = date('y-m-d', strtotime('+2 days'));
        //Inserting Data into table
//        $sql = "Insert Into trans_locks(trans_id,so_id,emp_id,status,parent_prop_id,"
//                . "requestor_id,lock_start_date,lock_end_date) "
//                . "Values('$lv_trans_id','$fp_v_so_id','$fp_v_emp_id','S121',"
//                . "'$lv_prop_id','$fp_v_requestor_id','$lv_start_date','$lv_end_date')";
//
//        $lv_result = cl_DB::postResultIntoTable($sql);
        
        $data = array(
        
        self::C_TRANS_ID => $lv_trans_id,
        self::C_ARR_SO_ID => $fp_v_so_id,
        self::C_ARR_EMP_ID =>$fp_v_emp_id,
        self::C_FNAME_STATUS => self::C_STATUS_SOFT_LOCK,
        self::C_FNAME_PROP_ID =>$lv_prop_id,
        self::C_REQUESTOR_ID =>$fp_v_requestor_id,
        self::C_FNAME_LOCK_START_DATE =>$lv_start_date ,
        self::C_FNAME_LOCK_END_DATE =>$lv_end_date,
        self::C_FNAME_ALLOW_MULTI =>$fp_v_Multi
        );

       return $this->db->insert(self::C_TABNAME, $data);
        
        

        //return true or false
    }
    
//    f
//    rmt/api/v1/processLock/?lock_id=123&action='approve'&comments='Approved'
//Lock History
    public function setLockHistory($lv_trans_id, $fp_v_so_id, $fp_v_emp_id, $status, $lv_prop_id, $lv_request_id) {
        $lv_start_date = date('y-m-d');
        $lv_end_date = date('y-m-d', strtotime('+2 days'));

//        $lv_sql = "Insert into trans_locks_history(trans_id,so_id,emp_id,status,parent_prop_id,"
//                . "requestor_id,lock_start_date,lock_end_date) "
//                . "VALUES($lv_trans_id,$lv_so_id,$lv_emp_id,'$status',$lv_prop_id,"
//                . "$lv_request_id,'$lv_start_date','$lv_end_date')";
//
//        $lv_result = cl_DB::postResultIntoTable($lv_sql);
//
//        return $lv_result;
         $data = array(
        
        self::C_TRANS_ID => $lv_trans_id,
        self::C_ARR_SO_ID => $fp_v_so_id,
        self::C_ARR_EMP_ID =>$fp_v_emp_id,
        self::C_FNAME_STATUS => $status,
        self::C_FNAME_PROP_ID =>$lv_prop_id,
        self::C_REQUESTOR_ID =>$lv_request_id,
        self::C_FNAME_LOCK_START_DATE =>$lv_start_date ,
        self::C_FNAME_LOCK_END_DATE =>$lv_end_date
        );
        return $this->db->insert(self::C_TABNAMEHISTORY, $data);
    }
    

    public function ApproveSoftLock($fp_arr_so, $fp_arr_emp, $fp_arr_stat, $lv_prop_id,$fp_arr_Multi ) {
       
//        $lv_obj = new cl_DB();
//        $lv_db = $lv_obj->getDBHandle();
//        echo "Array";
//        print_r($fp_arr_Multi);
//        echo "End Array";
        $softlock_count = 0;
        $rejected_proposal_count = 0;
        $res_count =[];
        $lv_count = count($fp_arr_so);
        for ($i = 0; $i < $lv_count; $i++) {
           
            if ($fp_arr_stat[$i] == 'Approve') {
                $lv_request_id = 444; //now passing Default request id but need to fetch dynamically
                //$lv_value = self::setTransId(); // find max trans_id
                   $lv_trans_id = self::setTransId();
//                foreach ($lv_value as $key => $value) { //generate new trans_id
//                    $lv_trans_id = $value['MAX(trans_id)'];
//                }
                $lv_trans_id++; //newly generated trans id

//
//                try {
                    
//                    mysqli_autocommit( $lv_db,false);
//                    mysqli_begin_transaction($lv_db);
                    $this->db->trans_start();
                    
                    $lv_app_result = self::setSoftlock($lv_trans_id, $fp_arr_so[$i], $fp_arr_emp[$i], $lv_prop_id, $lv_request_id,$fp_arr_Multi[$i]);
                    
                    $lv_history = self::setLockHistory($lv_trans_id, $fp_arr_so[$i], $fp_arr_emp[$i], self::C_STATUS_SOFT_LOCK, $lv_prop_id, $lv_request_id);
                        
                       
                         
                        // if allow multi is set to false update and set allow multi = false  all the entries in translocks for that employee 
                        if ($fp_arr_Multi[$i] == '')
                                 {
                                         $data = array(
                                        self::C_FNAME_ALLOW_MULTI =>''                                                                              
                                                        );

                                $this->db->where(self::C_ARR_EMP_ID, $fp_arr_emp[$i]);
                                $this->db->update(self::C_TABNAME, $data); 

                                 }
                                 
                        $this->db->trans_complete();         
//                   
                  
                        if ($this->db->trans_status() === TRUE)
                    {
                    $softlock_count++;      //counting no of soft lock
                    
                    
                    $lv_link = self::getLink($fp_arr_so[$i], $fp_arr_emp[$i],$lv_trans_id);
                   
                   $this->m_Notifications->sendSoftLockNotification($fp_arr_so[$i],$lv_link,$fp_arr_emp[$i],$lv_trans_id);                  
                    }
                    else if($this->db->trans_status() === FALSE)
                    {
                    Echo "transaction failed";
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
        
//        $sql1 = "UPDATE trans_locks SET status='S201',lock_start_date ='$fp_sdate',lock_end_date = '$fp_edate' WHERE trans_id = $fp_v_lock_trans_id";
//        $re_sos = cl_DB::updateResultIntoTable($sql1); 
    $data = array(

    self::C_FNAME_STATUS  =>  self::C_STATUS_HARD_LOCK  ,

    self::C_FNAME_LOCK_START_DATE =>  $fp_sdate,

    self::C_FNAME_LOCK_END_DATE   =>  $fp_edate

);

$this->db->where(self::C_TRANS_ID, $fp_v_lock_trans_id);

$this->db->update(self::C_TABNAME, $data);

        //print_r($re_sos);
    }
    public function setComments($lv_trans_id, $fp_v_status,$fp_v_comments,$lv_smart_project_code,$lv_FTE,$lv_tag_type)
    {
        
        //$sql1 ="INSERT INTO `rmg_tool`.`trans_comment` (`trans_id`, `status`, `comment`) VALUES ($lv_trans_id, $fp_v_status, $fp_v_comments);";
//        $sql = "insert into trans_commit('trans_id', 'status', 'comment') values($lv_trans_id, $fp_v_status,$fp_v_comments)";
//        $sql = "INSERT INTO `rmg_tool`.`trans_comment` (`trans_id`, `status`, `comment`) VALUES($lv_trans_id,'$fp_v_status','$fp_v_comments')";
//        $lv_result = cl_DB::updateResultIntoTable($sql);
         $data = array(
        self::C_TRANS_ID => $lv_trans_id,
        self::C_COMMENT_STATUS => $fp_v_status,
        self::C_FNAME_COMMENTS => $fp_v_comments,
        self::C_SMART_PROJECT_CODE =>  $lv_smart_project_code,
        self::C_FTE => $lv_FTE,
        self::C_TAG_TYPE => $lv_tag_type
             
        );
        
        $this->db->insert(self::C_TABNAMECOMMENTS, $data);
        
    }
    public function setRejectionCount($lv_so_no)
    {
      
        $lv_result = false;
//        $select = "SELECT `so_no` FROM `trans_count`";
//        self::$arr_SO_that_rejectedemps = cl_DB::getResultsFromQuery($select);
        
        $this->db->select(self::C_SO_NO);
        $query = $this-> db-> get(self::C_TABNAME_COUNT);
         self::$arr_SO_that_rejectedemps = $query->result_array();
        
//        print_r(self::$arr_SO_that_rejectedemps);
        foreach (self::$arr_SO_that_rejectedemps as $key => $value){
           
         if (in_array($lv_so_no, $value) == true) {
//            
//             $update = "UPDATE `trans_count` SET `rej_count`= rej_count + 1 WHERE so_no = $lv_so_no";
//             $lv_result = cl_DB::updateResultIntoTable($update);
//             return $lv_result;
             
             $data = array(
            self::C_FNAME_REJ_COUNT  =>  rej_count+1         
             );

               $this->db->where(self::C_SO_NO, $lv_so_no);

                return $this->db->update(self::C_TABNAME_COUNT, $data);
             
         }
        }
         
//              echo "chk point 2 there" ;
//        $insert= "INSERT INTO `rmg_tool`.`trans_count` (`so_no`, `rej_count`) VALUES ($lv_so_no, 1)";
//        $lv_result = cl_DB::updateResultIntoTable($insert);
         $data = array(
             self::C_SO_NO => $lv_so_no,
             self::C_FNAME_REJ_COUNT   =>  1
             
             );
         return $this->db->insert(self::C_TABNAME_COUNT,$data);        
       
    }
    public function getRejectionCount($lv_so_id)
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
//        $select = "SELECT * FROM `trans_count`";
//        $result = cl_DB::getResultsFromQuery($select);
         
        $query = $this->db->get(self::C_TABNAME_COUNT);
        $result = $query->result_array();

        
        
        foreach($result as $key => $value)
        {
            if (in_array($lv_so_id, $value))
            {
            $count = $value[self::C_FNAME_REJ_COUNT];
            }
        } 
        
        
        return $count;
        
        
        
        
    }
    /*hardlocks an employee  
      returns 1 if successfull
     returns -1 if failed     */
public function ApproveHardLock($fp_v_lock_trans_id,$fp_v_comments,$lv_smart_project_code,$lv_FTE,$lv_tag_type) {
   
      
//        $lv_obj = new cl_DB();
//        $lv_db = $lv_obj->getDBHandle();
        $lv_trans_result = self::getTransDetails($fp_v_lock_trans_id);
        foreach ($lv_trans_result as $key => $value) {
            $lv_trans_id = $value[self::C_TRANS_ID];
            $lv_so_id = $value[self::C_ARR_SO_ID];
            $lv_emp_id = $value[self::C_ARR_EMP_ID];
            $lv_status = $value[self::C_FNAME_STATUS];
            $lv_prop_id = $value[self::C_FNAME_PROP_ID];
            $lv_req_id = $value[self::C_REQUESTOR_ID];
//            $lv_start_date = $value['lock_start_date'];
//            $lv_end_date = $value['lock_end_date'];
//            $lv_up_by = $value['updated_by'];
        }
     
        $fp_sdate = self::getsostartdate($lv_so_id);
       
        $fp_edate = self::getsoenddate($lv_so_id);
       
        // get other locks acquired for same Emp
        $lv_alredy_proposed  = self::getDetailsWhereEmpIsAlreadyProposed($lv_emp_id);
        
        
        
        
        
        if($lv_status == self::C_STATUS_SOFT_LOCK )
            {
//        try {
//            mysqli_begin_transaction($lv_db);
            $this->db->trans_start();
            $lv_set_hardlock = self::setHardLock($lv_trans_id,$fp_sdate,$fp_edate);
            $lv_history = self::setLockHistory($lv_trans_id, $lv_so_id, $lv_emp_id, self::C_STATUS_HARD_LOCK, $lv_prop_id, $lv_req_id);
          
            $lv_comments = self::setComments($lv_trans_id,self::C_STATUS_HARD_LOCK,$fp_v_comments,$lv_smart_project_code,$lv_FTE,$lv_tag_type);
           
            
            // change the status of other locks which were acquired for same Employee
            foreach($lv_alredy_proposed as $key => $value )
            {
                $data = array(
                   self::C_FNAME_STATUS => self::C_STATUS_SOFT_LOCK_EXPIRED_DUE_TO_OTHERS_APPROVAL 
                ) ;
                $this->db->where(self::C_TRANS_ID,$value[self::C_TRANS_ID]);
                $this->db->update(self::C_TABNAME,$data);
                
                /*
                 * logic to send mail to owners of other So's
                 */
              
            }
            
//            mysqli_commit($lv_db);
            $this->db->trans_complete();
            if($this->db->trans_status() === TRUE)
            {
            return 1;
            }
            else
            {
                return -1;
            }
//        } catch (Exception $ex) {
////            mysqli_rollback($lv_db);
//            echo 'Failed-' . $ex->getMessage();
//            return -1;
//        }
            
        }
        else
        {
            //echo "Slock expired";
              return -1;            
        }
    }
    public function rejectProposal($fp_v_proposal_id, $fp_v_emp_id, $fp_v_so_id) {
//        $lv_query = "update trans_proposals SET rejected = 'X' where prop_id ='$fp_v_proposal_id'
//and emp_id ='$fp_v_emp_id'
//and so_id ='$fp_v_so_id'";
        $data1 = array(
           m_proposals::C_FNAME_PROPOSAL_ID => $fp_v_proposal_id, 
           m_proposals::C_FNAME_EMP_ID => $fp_v_emp_id,
            m_proposals::C_FNAME_SO_ID =>$fp_v_so_id
        );
        $this->db->where($data1);
        $data = array(
              m_proposals::C_FNAME_REJECTED => 'X',   
        );
        
       $re_sos = $this->db->update(m_proposals::C_TABNAME,$data);
        

     //   $re_sos = cl_DB::updateResultIntoTable($lv_query);
       

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
    
    public function rejectSoftLock($fp_v_lock_trans_id,$fp_v_comments,$lv_smart_project_code,$lv_FTE,$lv_tag_type) {

        
//        $lv_obj = new cl_DB();
//        $lv_db = $lv_obj->getDBHandle();
        $lv_trans_result = self::getTransDetails($fp_v_lock_trans_id);
        foreach ($lv_trans_result as $key => $value) {
            $lv_trans_id = $value[self::C_TRANS_ID];
            $lv_so_id = $value[self::C_ARR_SO_ID];
            $lv_emp_id = $value[self::C_ARR_EMP_ID];
               $lv_status = $value[self::C_ARR_STAT];
            $lv_prop_id = $value[self::C_PROP_ID];
            $lv_req_id = $value[self::C_REQUESTOR_ID];
//            $lv_start_date = $value['lock_start_date'];
//            $lv_end_date = $value['lock_end_date'];
//            $lv_up_by = $value['updated_by'];
        }
        if($lv_status==self::C_STATUS_SOFT_LOCK){
        try {
//            mysqli_begin_transaction($lv_db);
           // $sql = "UPDATE trans_locks SET status='S221' WHERE trans_id = $fp_v_lock_trans_id";
             $this->db->trans_start();
            $this->db->where(self::C_TRANS_ID,$fp_v_lock_trans_id);
            $data = array(
                self::C_FNAME_STATUS =>self::C_STATUS_SOFT_LOCK_REJECTED 
            );
            
            $re_sos =$this->db->update(self::C_TABNAME,$data);
            
            
            //$re_sos = cl_DB::updateResultIntoTable($sql);
            $lv_history = self::setLockHistory($lv_trans_id, $lv_so_id, $lv_emp_id, self::C_STATUS_SOFT_LOCK_REJECTED, $lv_prop_id, $lv_req_id);
            //$lv_comments = self::setComments($lv_trans_id,'S221',$fp_v_comments);
             $lv_comments = self::setComments($lv_trans_id,self::C_STATUS_SOFT_LOCK_REJECTED,$fp_v_comments,$lv_smart_project_code,$lv_FTE,$lv_tag_type);
            $lv_result = self::setRejectionCount($lv_so_id);
            $lv_Rej_count = self ::getRejectionCount($lv_so_id);
            $this->db->trans_complete();
           
            
            if($lv_Rej_count >= 3 && $this->db->trans_status() === TRUE)
            {
                //method to send mail to so_owner to clsoe the SO
                 $this->m_Notifications->sendSORejectionNotification($lv_so_id, $lv_emp_id,  $lv_trans_id);
            }
            
            
//            mysqli_commit($lv_db);
            return 1;
        } catch (Exception $ex) {
//            mysqli_rollback($lv_db);
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
//        $sql = "SELECT * FROM `trans_locks` WHERE trans_id = $fp_v_trans_id";
//        $lv_result = cl_DB:: getResultsFromQuery($sql);
//        return $lv_result;
        $this->db->where(self::C_TRANS_ID,$fp_v_trans_id);
        $query = $this->db->get(self::C_TABNAME);
        return $query->result_array();
    }
    public function getsostartdate($fp_v_so_id)
    {
//        $sql = "SELECT so_sdate FROM `v_open_so` WHERE so_no = $fp_v_so_id";
//        $lv_result = cl_DB:: getResultsFromQuery($sql);
        
        $this->db->where(m_open_so::C_FNAME_SO_POS_NO,$fp_v_so_id );
        $this->db->select(m_open_so::C_FNAME_START_DATE);

        $query = $this->db->get(getDetails::C_SO_MASTER);
//        echo $this->db->last_query();
        $lv_result = $query->result_array();
        foreach ($lv_result as $key => $value)
        {
            $so_sdate = $value[m_open_so::C_FNAME_START_DATE];
        }
        
        
        return $so_sdate;
    }
    public function getsoenddate($fp_v_so_id)
    {
        
//        $sql = "SELECT so_endate FROM `v_open_so` WHERE so_no = $fp_v_so_no";
//        $lv_result = cl_DB:: getResultsFromQuery($sql);
        $this->db->where(m_open_so::C_FNAME_SO_POS_NO,$fp_v_so_id );
        $this->db->select(m_open_so::C_FNAME_END_DATE);

        $query = $this-> db-> get(getDetails::C_SO_MASTER);

        $lv_result = $query->result_array();
        
        foreach ($lv_result as $key => $value)
        {
            $so_endate = $value[m_open_so::C_FNAME_END_DATE];
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
            $lv_proj_code = $value[m_open_so::C_FNAME_PROJ_ID];
            $lv_proj_name = $value[m_open_so::C_FNAME_PROJ_NAME];
            $lv_so_no = $value[m_open_so::C_FNAME_SO_POS_NO] ;
          //  $lv_sdate = $value['so_sdate'];
            $lv_sdate = $value[m_open_so::C_FNAME_START_DATE];
            //$lv_edate = $value['so_endate'];
            $lv_edate = $value[m_open_so::C_FNAME_END_DATE];
        }
        //echo $lv_proj_code;
        foreach($lt_empdetails as $key => $value)
        {
            $lv_bu = $value[m_BuEmployees::C_FNAME_IDP];
            $lv_sub_bu = $value[m_BuEmployees::C_FNAME_SUBBU];
            $lv_svc_line = $value[getDetails::C_EMP_PRIME_SKILL];
            $lv_loc = $value[m_BuEmployees::C_FNAME_ORG];
            $lv_emp_id = $value[m_BuEmployees::C_FNAME_EMP_ID];
            $lv_emp_name = $value[m_BuEmployees::C_FNAME_EMP_NAME];
            $lv_prime_skill = $value[getDetails::C_EMP_SVC_LINE];
            $lv_lvl = $value[getDetails::C_EMP_LVL];
        }
        
        //$lv_link = "http://localhost/rmt1/UI/buttons_rmt/WebContent/approve.php/?bu=$lv_bu&subbu=$lv_sub_bu&svcline=$lv_svc_line&loc=$lv_loc&emp_id=$lv_emp_id&emp_name=$lv_emp_name&lv_prime_skill=$lv_prime_skill&lvl=$lv_lvl&proj_code=$lv_proj_code&proj_name=$lv_proj_name&so_no=$lv_so_no&sdate=$lv_sdate&edate=$lv_edate";
      
        $lv_link = "http://10.74.163.157:8080/rmt/UI/buttons_rmt/WebContent/approve.php/?bu=$lv_bu&subbu=$lv_sub_bu&svcline=$lv_svc_line&loc=$lv_loc&emp_id=$lv_emp_id&emp_name=$lv_emp_name&lv_prime_skill=$lv_prime_skill&lvl=$lv_lvl&proj_code=$lv_proj_code&proj_name=$lv_proj_name&so_no=$lv_so_no&sdate=$lv_sdate&edate=$lv_edate&trans_id=$fp_v_trans_id";
        return $lv_link;
        
    }
    
    public function getDetailsWhereEmpIsAlreadyProposed($fp_v_emp_id)
    {   
        $lv_emp_id = $fp_v_emp_id;
        $lv_emp_name = '';
        $arr_result = [];
        
        
        // get all sos where the employee is currently proposed
        $array = array( self::C_ARR_EMP_ID => $fp_v_emp_id, self::C_FNAME_STATUS => self::C_STATUS_SOFT_LOCK);

        $this->db->where($array); 
//        $this->db->where(m_open_so::C_FNAME_SO_POS_NO,$fp_v_so_id );
        $this->db->select(self::C_ARR_SO_ID);
        $this->db->select(self::C_TRANS_ID);
        $query = $this-> db-> get(self::C_TABNAME);
//       echo $this->db->last_query();
        $arr_so_id = $query->result_array();
        
        // get Emp name from emp id
        
         $array1 = array( self::C_ARR_EMP_ID => $fp_v_emp_id);
           $this->db->where($array1); 
           $this->db->select(getDetails::C_FNAME_EMPNAME);
          $getEmpName = $this->db->get(getDetails::C_EMP_MASTER);
        
         $arr_emp = $getEmpName->result_array();
        
         foreach ($arr_emp as $key => $value) {
             $lv_emp_name = $value[getDetails::C_FNAME_EMPNAME];
             
         }
         
         // get SO details from soid and construct the result Array 

       
        foreach ($arr_so_id as $key => $value) {
         $arr_result[] = getDetails::getSODetails($value[self::C_ARR_SO_ID]);
         $arr_result[$key][0][self::C_TRANS_ID] = $value[self::C_TRANS_ID]; 
        }
        
        $arr_result[self::C_ARR_EMP_ID] = $lv_emp_id;
        $arr_result[getDetails::C_FNAME_EMPNAME] = $lv_emp_name;
        
        
        return $arr_result;
        
    }
    //Reports:
//   public function getSoftLocked($fp_v_start_date, $fp_v_end_date)
//   {
//       $lv_query = 'SELECT '.PHP_EOL
//                   . '* '.PHP_EOL
//                   . 'FROM'.PHP_EOL
//                   . self::C_TABLE_NAME.PHP_EOL
//                   . 'WHERE'.PHP_EOL
//                   . self::C_FNAME_STATUS.' = '.self::C_STATUS_SOFT_LOCK.PHP_EOL
//                   . 'AND'.PHP_EOL 
//                   . self::C_FNAME_LOCK_END_DATE.PHP_EOL
//                   .'BETWEEN '.PHP_EOL
//                   . 'CAST('."'$fp_v_start_date'".') AS DATE'.PHP_EOL
//                   . 'AND CAST('."'$fp_v_end_date'".') AS DATE'.PHP_EOL;
//       $lo_DB   = new cl_DB();
//       $re_data = $lo_DB->getResultsFromQuery($lv_query);
//       return $re_data;
//   }
//   
//   public function getHardLocked($fp_v_start_date, $fp_v_end_date)
//   {
//       
//   }
//   
//   public function getSoftLockReleased($fp_v_start_date, $fp_v_end_date)
//   {
//       
//   }
//   
//   public function getHardLockReleased($fp_v_start_date, $fp_v_end_date)
//   {
//       
//   }
//   
//   private function getLockData(array $fp_arr_statuses, $fp_v_start_date, $fp_v_end_date)
//   {
//       $lv_startDate = $fp_v_start_date;
//       $lv_endDate   = $fp_v_end_date;
//       /**
//        * If dates are invalid, default start and end dates to today's date
//        */
//       if(cl_abs_QueryBuilder::isDateRangeValid($fp_v_start_date, $fp_v_end_date)=== false)
//       {
//           $lv_startDate = date(cl_abs_QueryBuilder::C_DATE_FORMAT);
//           $lv_endDate   = $lv_startDate;
//       }
//       $lv_startDate = cl_abs_QueryBuilder::getSQLDateFromString($lv_startDate);
//       $lv_endDate   = cl_abs_QueryBuilder::getSQLDateFromString($lv_endDate);
//        
//       $lv_statusClause    = cl_abs_QueryBuilder::getInQuery(self::C_FNAME_STATUS, $fp_arr_statuses);
//       $lv_end_date_clause = cl_abs_QueryBuilder::getBetweenFilterQuery
//                            (self::C_FNAME_LOCK_END_DATE,$lv_startDate,$lv_endDate );        
//       $lv_query = 'SELECT'.PHP_EOL
//                    .'*'   .PHP_EOL
//                   .'FROM' .PHP_EOL
//                   .self::C_TABLE_NAME.PHP_EOL
//                   .'WHERE'.PHP_EOL
//                   .    $lv_statusClause.PHP_EOL
//                   .    cl_abs_QueryBuilder::C_SQL_AND.PHP_EOL
//                   .    $lv_end_date_clause.PHP_EOL;
//       $lo_DB = new cl_DB(); 
//       $re_data = $lo_DB->getResultsFromQuery($lv_query);
//       return $re_data;
//   }    
    
    public function gethardlockdata($fp_start_date , $fp_end_date){
       $arr_result = [];
       $this->db->select('emp_id,emp_name,svc_line ,curr_proj_code,curr_proj_name,curr_start_date,curr_end_date,so_id,so_level ,sup_id,sup_name,smart_proj_code,FTE,tag_type');
       $this->db->from(self::C_V_hard_lock);
       $this->db->where('updated_on >=',$fp_start_date); 
       $this->db->where('updated_on <=',$fp_end_date);
       $arr_result = $this->db->get();
       $arr_result_final = $arr_result->result_array();     
    return $arr_result_final;
           
    }
}