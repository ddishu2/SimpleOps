<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'cl_abs_QueryBuilder.php';
class cl_ammendments {

    const C_COMMENTS = 'comments';
    const C_EMP_ID = 'emp_id';
    const C_STAT = 'status';
    //const C_AMMEND_TABLE = 'pass_data';
    const AMENDMENTS_TABNAME = 'm_ammendment';
    
    
    
    const C_COMPETENCY = 'competency';
    const C_CUST_NAME = 'cust_name';
    const C_PROJ_NAME = 'proj_name';
   
//    const C_AMMEND_TABLE = 'ammend_table';
    private static $arr_amendments = [];
    private static $arr_amendments_decision_taken = [];
    
    public static function get_ammendments_decision_taken()
    {
        
//        $date = date('y-m-d');
//        $sql = "SELECT id FROM `trans_ammendment` WHERE 'Updated On' = $date ";
          $sql = "SELECT `id` FROM `trans_ammendment` WHERE `Updated On` = CURRENT_DATE";
        self::$arr_amendments_decision_taken = cl_DB::getResultsFromQuery($sql);
        //return self::$arr_amendments_decision_taken;
    }
    
   public static function isProcessed($fp_emp_id)
   {
      
       $lv_result = false;
       foreach (self::$arr_amendments_decision_taken as $key => $value) {
           if(in_array($fp_emp_id, $value))
           {
               $lv_result = true;
               break;
           }
       }
       return $lv_result;
   }
 
   private static function isfilterset($fp_filtervalue)
   {
       $filter_set = false;
       if(!($fp_filtervalue == ''|| $fp_filtervalue == null)){
            $filter_set = true;
       }
       return $filter_set;
   }
   private static function getQuery($fp_cust_name,$fp_proj_name,$fp_arr_competency)
   {
        $sql  = "SELECT * FROM `v_amendment`";
         
         
         
         
         $filter_cust_name  = self::isfilterset($fp_cust_name);
         $filter_curr_proj_name = self::isfilterset($fp_proj_name);
         $filter_competency = self::isfilterset($fp_arr_competency); 
         
//         echo $filter_competency;
//         print_r($fp_arr_competency);
         
         
         if($filter_cust_name){
             $sql = $sql."where  v_amendment.cust_name = '$fp_cust_name'";
             
             //$filter_cust_name = true;
         }
         
         
         
         
         if($filter_curr_proj_name){
              if($filter_cust_name){
                $sql = $sql."and v_amendment.curr_proj_name = '$fp_proj_name'";  
              }
              else
              {
             $sql = $sql."where  v_amendment.curr_proj_name = '$fp_proj_name' ";
              }
            // $filter_curr_proj_name = true;
         }
         
         
         
         
         if($filter_competency)
         {
             
             
             
//             $arr_competency_count = count($fp_arr_competency);
//             $arr_values = "(";
//             for($i=0 ; $i<$arr_competency_count ; $i++)
//             {
//                 
//                 $value = $fp_arr_competency[$i];
//                 $arr_values = $arr_values.'$value' .",";
//             }
//            rtrim($arr_values, ",");
//            $arr_values = $arr_values.")";
//            
            
            
            
            $in_query = cl_abs_querybuilder::getInquery('competency',$fp_arr_competency);
              if( !($in_query== '' || $in_query== null))
              {
             if($filter_cust_name || $filter_curr_proj_name)
             {
                 $sql = $sql."and v_amendment.".$in_query;
             }
             else 
             {
                 $sql = $sql."where v_amendment. ".$in_query;
             }
         }
         }
         
         return $sql;
   }
   
    public static function getAmmendments($fp_cust_name,$fp_proj_name,$fp_arr_competency) {
        self::get_ammendments_decision_taken();
        $re_ammendments = [];
        
//        $sql = "SELECT * FROM `m_ammendment` ";
        
        $sql = self::getQuery($fp_cust_name,$fp_proj_name,$fp_arr_competency);
        
//         echo $sql;
         
         
        $re_result = cl_DB::getResultsFromQuery($sql);
//      print_r($re_ammendments);

        
        foreach ($re_result as $key => $value) {


//            if (((!$value['new_edate'] == '') || (!$value['new_sup_id'] == 0)) && (!self::isProcessed($value['id']))) {
                if (!self::isProcessed($value['id'])){
                $re_ammendments[] = $value;
            }
        }



        return $re_ammendments;
    }
     
    
    
//    public function ApproveAmmendments($fp_Arr_result) {
//
//        $lv_Approved_count = 0;
//        $lv_reject_count = 0;
//        $lv_res_count = [];
//        $lv_count = count($fp_Arr_result);
//        // for ($i = 0; $i < $lv_count; $i++) {
////        foreach ($fp_Arr_result as $key => $value) {
////            //if ($fp_arr_stat[$i] == 'Approve')
////            if ($value['status'] == 'Approve') {
////                self::updateAmmendmentsandmail($value);
////
////                $lv_Approved_count ++;
////            } else if ($value['status'] == 'Reject') {
////                self::updateAmmendmentsandmail($value);
////                $lv_reject_count++;
////            }
////        }
//        for ($i = 0; $i < $lv_count; $i++){
//            if($fp_Arr_result[$i]['am_status'] == 'Approve')
//            {
//                self::updateAmmendmentsandmail($fp_Arr_result[$i]);
////
//                $lv_Approved_count ++;
//            }
//             else if ($fp_Arr_result[$i]['am_status'] == 'Reject') {
//               self::updateAmmendmentsandmail($fp_Arr_result[$i]);
//                $lv_reject_count++;
//           }
//        }
//        
//        
//        
//        $lv_res_count['Approved'] = $lv_Approved_count;
//        $lv_res_count['Rejected'] = $lv_reject_count;
//
//        return $lv_res_count;
//    }

        public function ApproveAmmendments($fp_arr_emp_id, $fp_arr_comments,$fp_arr_stat) {
            

        $lv_Approved_count = 0;
        $lv_reject_count = 0;
        $lv_res_count = [];
        $lv_count = count($fp_arr_emp_id);
         for ($i = 0; $i < $lv_count; $i++) {
              
       // foreach ($fp_Arr_result as $key => $value) {
            $value = self::getAmmendmentsdetails($fp_arr_emp_id[$i]);
         
            if ($fp_arr_stat[$i] == 'Approve'){
            //if ($value['status'] == 'Approve') {
                
                self::updateAmmendmentsandmail($value, $fp_arr_comments[$i],$fp_arr_stat[$i]);

                $lv_Approved_count ++;
            } 
            //else if ($value['status'] == 'Reject') {
            elseif ($fp_arr_stat[$i] == 'Reject'){
                 
                self::updateAmmendmentsandmail($value, $fp_arr_comments[$i],$fp_arr_stat[$i]);
                $lv_reject_count++;
            }
        }
//        for ($i = 0; $i < $lv_count; $i++){
//            if($fp_Arr_result[$i]['am_status'] == 'Approve')
//            {
//                self::updateAmmendmentsandmail($fp_Arr_result[$i]);
////
//                $lv_Approved_count ++;
//            }
//             else if ($fp_Arr_result[$i]['am_status'] == 'Reject') {
//               self::updateAmmendmentsandmail($fp_Arr_result[$i]);
//                $lv_reject_count++;
//           }
//        }
        
        
        
        $lv_res_count['Approved'] = $lv_Approved_count;
        $lv_res_count['Rejected'] = $lv_reject_count;

        return $lv_res_count;
    }
    
    
    
    
    
    private function updateAmmendmentsandmail($fp_arr_result,$fp_comments,$fp_stat) {
        $this->popExistingAmendments();
        
    

//        $lv_id = $fp_arr_result['am_id'];
//        $lv_name = $fp_arr_result['am_name'];
//        $lv_level = $fp_arr_result['am_lev'];
//        $lv_IDP = $fp_arr_result['am_idp'];
//        $lv_loc = $fp_arr_result['am_location'];
//        $lv_bill_stat = $fp_arr_result['am_bilstat'];
//        $lv_competency = $fp_arr_result['am_compet'];
//        $lv_curr_proj_name = $fp_arr_result['am_currproname'];
//
//
//
//
//        //change string date to date format 
//        $lv_csdate = $fp_arr_result['am_startdate'];
//        $lv_curr_sdate = date('y-m-d', strtotime($lv_csdate));
//
//
//        //change string date to date format 
//        $lv_cedate = $fp_arr_result['am_enddate'];
//        $lv_curr_edate = date('y-m-d', strtotime($lv_cedate));
//
//
//
//
//         //change string date to date format 
//        $lv_proj_edate = $fp_arr_result['am_projected'];
//        $lv_proj_edate_projected = date('y-m-d', strtotime($lv_proj_edate));
//
//
//
//
//        $lv_supervisor = $fp_arr_result['am_sup'];
//        $lv_cust_name = $fp_arr_result['am_customer'];
//        $lv_domain_id = $fp_arr_result['am_dom'];
//
//
//
//        $lv_nedate = $fp_arr_result['am_newdate'];
//        $lv_new_edate = date('y-m-d', strtotime($lv_nedate));
//
//
//
//
//        $lv_new_sup_corp_id = $fp_arr_result['am_newcorp'];
//        $lv_new_sup_id = $fp_arr_result['am_newsupid'];
//        $lv_new_sup_name = $fp_arr_result['am_supname'];
//        $lv_reason = $fp_arr_result['am_reason'];
//        $lv_req_by = $fp_arr_result['am_request'];
//
//        $lv_status = $fp_arr_result['am_status'];
//        $lv_ops_comments = $fp_arr_result['am_comments'];
        
        
        
        
        
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




//        $lv_supervisor_lname = $fp_arr_result['supervisor_lname'];
//        $lv_supervisor_fname = $fp_arr_result['supervisor_fname'];
         
//        $lv_supervisor = $fp_arr_result['supervisor_lname'].$fp_arr_result['supervisor_fname'];
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

        $lv_status = $fp_stat;
        $lv_ops_comments = $fp_comments;
        
        
        
        
        
        
        
        
        
        
        
        
        $lv_Updated_On = date('y-m-d');
        
        $flag = "false";
        foreach (self::$arr_amendments as $key => $value) {
            if(in_array($lv_id, $value))
            {
                $flag = "True";
                break;
            }
            
        }
        // if existing then update else insert
       if($flag == "false"){
        $sql = "INSERT INTO `rmg_tool`.`trans_ammendment` (`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`, `proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`,`Updated On`)"
                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate', '$lv_new_sup_corp_id', '$lv_new_sup_id', '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments','$lv_Updated_On')";
       }
       else 
       {
          $sql = "UPDATE `trans_ammendment` SET `new_edate`='$lv_new_edate',`new_sup_corp_id`= '$lv_new_sup_corp_id',`new_sup_id`='$lv_new_sup_id',`new_sup_name`='$lv_new_sup_name',`reason`='$lv_reason',`req_by`='$lv_req_by',`status`='$lv_status',`ops_comments`='$lv_ops_comments',`Updated On`='$lv_Updated_On' WHERE id = $lv_id"; 
       }


        $re_result = cl_DB::updateResultIntoTable($sql);
            
        $sql1 = "INSERT INTO `rmg_tool`.`trans_ammendment_history` (`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`, `proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`,`Updated On`)"
                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate', '$lv_new_sup_corp_id', '$lv_new_sup_id', '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments','$lv_Updated_On')";
        
        
        
        $re_result1 = cl_DB::updateResultIntoTable($sql1);
        
         $lo_mail = new cl_NotificationMails();
         
         
//         echo "lv_date". $lv_new_edate;
//         echo "corpid".$lv_new_sup_corp_id;
        if ($re_result && $re_result1) {
            if (($lv_nedate == '') && ($lv_new_sup_corp_id != '')) {
                // send mail for  change in supervisor;
              
               $lo_mail->sendTEApproverChangeNotification($fp_arr_result,$fp_stat,$fp_comments);
            } else if ($lv_nedate != '' && $lv_new_sup_corp_id == '') {
                // send mail for  change in enddate;
              
                $lo_mail->sendReleasedateChangeNotification($fp_arr_result,$fp_stat,$fp_comments);
            } elseif (!$lv_nedate == '' && !$lv_new_sup_corp_id == '') {
                // send mail for both change in supervisor and change in end  date
                $lo_mail->sendTEApproverChangeNotification($fp_arr_result,$fp_stat,$fp_comments);
                $lo_mail->sendReleasedateChangeNotification($fp_arr_result,$fp_stat,$fp_comments);
            }
        }
        return $re_result;
    }
    
    public function popExistingAmendments()
    {
        $sql = "SELECT id from trans_ammendment";
        self::$arr_amendments = cl_DB::getResultsFromQuery($sql);
        //return self::$arr_amendments;
    }

    public function getAmmendmentsdetails($fp_emp_id)
    {
       
        $sql = "SELECT * FROM `m_ammendment` WHERE id = $fp_emp_id ";
        $result= cl_DB::getResultsFromQuery($sql);
       
        foreach ($result as $key => $value) {
            $re_result = $value;
            
        }
        
        
        
        return $re_result;
    }
    
    public function getAmmendmentsReport()
    {
        $sql = "SELECT * FROM `trans_ammendment` where trans_ammendment.`Updated On` = CURRENT_DATE and trans_ammendment.status = 'Approve'";
         $result= cl_DB::getResultsFromQuery($sql);
         return $result;
    }
    
}
