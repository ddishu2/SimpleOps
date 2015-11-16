<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'cl_abs_QueryBuilder.php';
require_once 'cl_loadFiles.php';
class cl_ammendments {

    const C_COMMENTS = 'comments';
    const C_EMP_ID = 'emp_id';
    const C_STAT = 'status';
    //const C_AMMEND_TABLE = 'pass_data';
    const AMENDMENTS_TABNAME = 'm_ammendment';
    
    
    
    const C_COMPETENCY = 'competency';
    const C_CUST_NAME = 'cust_name';
    const C_PROJ_NAME = 'proj_name';
//    const AMENDMENT_BAT_SRC_DIR  = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\Ammendment\Batch Files\\';
    const AMENDMENT_BAT_SRC_DIR  ="D:\\xampp\\htdocs\\rmt\\api\\BatchFile\\amendments\\";
    const AMENDMENT_BAT_FILENAME_LOAD = 'rmt_amen_load_PHP.bat';
    const AMENDMENT_BAT_FILENAME_CREATE = 'rmt_amen_load_RAS.bat';
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
         
         
         
         
//         $filter_cust_name  = self::isfilterset($fp_cust_name);
        $filter_cust_name = false;
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
//      print_r($re_result);

        
        foreach ($re_result as $key => $value) {


//            if (((!$value['new_edate'] == '') || (!$value['new_sup_id'] == 0)) && (!self::isProcessed($value['id']))) {
                if (!self::isProcessed($value['id'])){
//                    if($key ==2 ) {
//                        $value['cust_name'] = 'tejas sss';
                      
                    unset($value['cust_name']);
                     $re_ammendments[] = $value;
//                       break;
//                    }  
                //print_r($value);die;
                //break;
            }
        }


        //unset($re_ammendments[0]['new_sup_corp_id']);
        //unset($re_ammendments[0]['new_sup_id']);
        //unset($re_ammendments[0]['new_sup_name']);
        //unset($re_ammendments[0]['reason']);
        //unset($re_ammendments[0]['curr_proj_name']);
        //unset($re_ammendments[0]['curr_sdate']);
        //unset($re_ammendments[0]['curr_edate']);
        //unset($re_ammendments[0]['supervisor']);
        //unset($re_ammendments[0]['proj_edate_projected']);
        //unset($re_ammendments[0]['cust_name']);
        //unset($re_ammendments[0]['ext_notice']);
        //unset($re_ammendments[0]['roll_off_lead_time']);
        //unset($re_ammendments[0]['new_edate']);
        //unset($re_ammendments[0]['roll_off_lead_time']);
//        [id] => 307
//            [name] => Sridhar S Iyer
//            [level] => M4
//            [IDP] => Appsone SAP
//            [loc] => Mumbai
//            [bill_stat] => Chargeable
//            [competency] => Finance
//            [curr_proj_name] => IN03_ALDI - SAP POC
//            [curr_sdate] => 14-Oct-15
//            [curr_edate] => 30-Nov-15
//            [proj_edate_projected] => 30-Nov-15
//            [supervisor] => Gokhale,Kaustubh
//            [domain_id] => sriiyer
//            [new_edate] => 30-Nov-16
//            [act] => Extension
//            [roll_off_lead_time] => 274
//            [ext_notice] => -261
//            [req_by] => tnakwa
        
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
        
//        $lv_cust_name = $fp_arr_result['cust_name'];
        $lv_cust_name = '';
        $lv_domain_id = $fp_arr_result['domain_id'];



        $lv_nedate = $fp_arr_result['new_edate'];
        $lv_new_edate = date('y-m-d', strtotime($lv_nedate));

         $lv_act = $fp_arr_result['act'];
//         $lv_date_chng_noti = $fp_arr_result['Date_chng_noti'];
           /* change
          * addition of new cols insted of Date_chang_noti
          */
         
        $lv_roll_off_lead_time = $fp_arr_result['roll_off_lead_time'];
        $lv_ext_notice = $fp_arr_result['ext_notice'];
        
        
        /*
         * end Change
         */


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
//        $sql = "INSERT INTO `trans_ammendment` (`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`, `proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`,`act`,`date_chng_noti`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`,`Updated On`)"
//                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate','$lv_act','$lv_date_chng_noti', '$lv_new_sup_corp_id', '$lv_new_sup_id', '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments','$lv_Updated_On')";
           $sql = "INSERT INTO `trans_ammendment` (`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`, `proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`,`act`,`roll_off_lead_time`,`ext_notice`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`,`Updated On`)"
                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate','$lv_act','$lv_roll_off_lead_time','$lv_ext_notice', '$lv_new_sup_corp_id', '$lv_new_sup_id', '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments','$lv_Updated_On')";
           }
       else 
       {
          $sql = "UPDATE `trans_ammendment` SET `new_edate`='$lv_new_edate',`new_sup_corp_id`= '$lv_new_sup_corp_id',`new_sup_id`='$lv_new_sup_id',`new_sup_name`='$lv_new_sup_name',`reason`='$lv_reason',`req_by`='$lv_req_by',`status`='$lv_status',`ops_comments`='$lv_ops_comments',`Updated On`='$lv_Updated_On' WHERE id = $lv_id"; 
       }

//         echo $sql;
        $re_result = cl_DB::updateResultIntoTable($sql);
        
        
        $sql_new_edate_adjust = "UPDATE `trans_ammendment` SET `new_edate`='0000-00-00' WHERE `new_edate`='1970-01-01';";    
        $re_result1 = cl_DB::updateResultIntoTable($sql_new_edate_adjust);
//        $sql1 = "INSERT INTO `rmg_tool`.`trans_ammendment_history` (`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`,`act`,`date_chng_noti`,`proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`,`Updated On`)"
//                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate','$lv_act','$lv_date_chng_noti','$lv_new_sup_corp_id', '$lv_new_sup_id', '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments','$lv_Updated_On')";
        
         $sql1 = "INSERT INTO `trans_ammendment_history`(`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`, `proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`,`act`,`roll_off_lead_time`,`ext_notice`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`,`Updated On`)"
                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate','$lv_act','$lv_roll_off_lead_time','$lv_ext_notice', '$lv_new_sup_corp_id', '$lv_new_sup_id', '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments','$lv_Updated_On')";
        
        $re_result1 = cl_DB::updateResultIntoTable($sql1);
        
        
        $sql_new_edate_adjust_history = "UPDATE `trans_ammendment_history` SET `new_edate`='0000-00-00' WHERE `new_edate`='1970-01-01';";    
        $re_result1 = cl_DB::updateResultIntoTable($sql_new_edate_adjust_history);
        
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
//    public function getAmmendmentsReport()
    public function getAmmendmentsReport($from_date,$to_date)
    {
       // $sql = "SELECT * FROM `trans_ammendment` where trans_ammendment.`Updated On` = CURRENT_DATE and trans_ammendment.status = 'Approve'";
       $sql = "SELECT * FROM `trans_ammendment` where trans_ammendment.`Updated On` >= '$from_date' and trans_ammendment.`Updated On` <= '$to_date' and trans_ammendment.status = 'Approve'";
         $result= cl_DB::getResultsFromQuery($sql);
         return $result;
    }
    
    
    
    public function loadAmendments()
    {
        $lv_batfile = //'start '.
                escapeshellarg(self::AMENDMENT_BAT_SRC_DIR.self::AMENDMENT_BAT_FILENAME_LOAD);
        
//        $lv_batfile = escapeshellarg('D:\xampp\htdocs\rmt\api\BatchFile\amendments\rmt_amen_load_PHP.bat');
        
        echo $lv_batfile;
        $str = exec($lv_batfile);
        
        $lv_amendment_loadFile = cl_loadFiles::loadAmendments();
    }
    public function createAmendmentsFile()
    {
        $lv_batfile = //'start '.
                escapeshellarg(self::AMENDMENT_BAT_SRC_DIR.self::AMENDMENT_BAT_FILENAME_CREATE);
        
        
//          $lv_batfile = escapeshellarg('D:\xampp\htdocs\rmt\api\BatchFile\amendments\rmt_amen_load_RAS.bat');
//        $lv_batfile = 'start /B \\\\10.75.250.149\Datagrp\AppsOne SAP RMT\Ammendment\Batch Files\rmt_amen_load_PHP.bat';
//        $lv_batfile = escapeshellarg($lv_batfile);
        echo $lv_batfile;
      
        //system("cmd /c C:\xampp\htdocs\rmt\api\BatchFile\SlockExpiry.bat");
        //$str = exec('start /B C:\xampp\htdocs\rmt\api\BatchFile\SlockExpiry.bat');
        //$str = exec('start /B '.self::AMENDMENT_BAT_FILENAME.self::AMENDMENT_BAT_FILENAME);
        $str = exec($lv_batfile);
        //echo $str;
    }
}
