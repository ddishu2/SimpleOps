<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of m_getamendment
 *
 * @author vkhisty
 */
//  $autoload["libraries"] = array("database");
class m_getamendment extends CI_model {
       
    const C_DATE_FORMAT = 'Y-m-d';
    const C_V_AMENDTMENT = 'v_amendment';
    const C_STAR = '*';
    const C_CUST_ID = 'id';
    const C_TRANS_AMMENDMENT = 'trans_ammendment';
    const C_CUST_NAME = 'cust_name';
    const C_V_AMDMENT_PROJ = 'v_amendment.curr_proj_name';
    const C_FNAME_UPDATED_ON = 'Updated_On';
    const C_COMPETENCY = 'competency';
    const C_Star = '*';
    const C_M_AMMENDMENT = '`m_ammendment`';
    const C_TRANS_AMMENDMENT = 'trans_ammendment';
    const C_ID = 'id';
    private static $arr_amendments = [];
  public function __construct() 
    {   
        $this->load->database();
//        $this->load->helper('date');
    }
private static $arr_amendments_decision_taken = [];
//  const C_DB_NAME        = "rmt_amendment_DB";
//const DB_EXCEPTION     = "Could not connect to database";
//const QUERY_EXCEPTION  = "Data Error: Bad Query"; 
//const C_DB_NAME        = "rmt_avi";
//const C_USER_NAME      = "root";
//const C_PASSWORD       = "";
//const C_QUERY_ERROR    = 'Error: No Records Found';
//const C_HOSTNAME       = "localhost";

   
  
 public  function get_ammendments_decision_taken(){
//       $sql = "SELECT `id` FROM `trans_ammendment` WHERE `Updated On` = CURRENT_DATE";
   $curr_date = date(self::C_DATE_FORMAT);
       $this->db->select(self::C_CUST_ID);
       $this->db->from(self::C_TRANS_AMMENDMENT);
       $this->db->where(self::C_FNAME_UPDATED_ON,$curr_date); 
       $query = $this->db->get();
       
       self::$arr_amendments_decision_taken = $query->result_array();
//       echo $this->db->last_query();
//       print_r(self::$arr_amendments_decision_taken);
 }  
 
    
 public  function getamnedments($fp_cust_name,$fp_proj_name,$fp_arr_competency)
 {
     self::get_ammendments_decision_taken();
        $re_ammendments = [];
        
$sql = self::getQuery($fp_cust_name,$fp_proj_name,$fp_arr_competency);
       $re_result = $sql->result_array();
////      print_r($re_result);
//
//        
       foreach ($re_result as $key => $value) {
//
//
////            if (((!$value['new_edate'] == '') || (!$value['new_sup_id'] == 0)) && (!self::isProcessed($value['id']))) {
               if (!self::isProcessed($value[self::C_CUST_ID])){
////                    if($key ==2 ) {
////                        $value['cust_name'] = 'tejas sss';
//                      
                    unset($value[self::C_CUST_NAME]);
                    $re_ammendments[] = $value;
                       break;
//                    }  
//                //print_r($value);die;
//                //break;
//      
            }
       }
        return $re_ammendments;
 }
   private  function getQuery($fp_cust_name,$fp_proj_name,$fp_arr_competency)
   {
//        $sql  = "SELECT * FROM `v_amendment`";
       $this->db->select(self::C_STAR);
       $this->db->from(self::C_V_AMENDTMENT);
       
//         $filter_cust_name  = self::isfilterset($fp_cust_name);
        $filter_cust_name = false;
         $filter_curr_proj_name = self::isfilterset($fp_proj_name);
         $filter_competency = self::isfilterset($fp_arr_competency);
         
         
         if($filter_curr_proj_name){
//             $sql = $sql."where  v_amendment.curr_proj_name = '$fp_proj_name' ";
                $this->db->where(self::C_V_AMDMENT_PROJ, $fp_proj_name);
         }
         if($filter_competency)
         {
//            $in_query = cl_abs_querybuilder::getInquery('competency',$fp_arr_competency);
             $this->db->where_in(self::C_COMPETENCY,$fp_arr_competency);
//             $in_query = $this->db->get();
              
             if($filter_cust_name || $filter_curr_proj_name)
             {
//                 $sql = $sql."and v_amendment.".$in_query;
                 $sql = $sql.$this->db->where(self::C_V_AMENDTMENT,$this->db->where_in(self::C_COMPETENCY,$fp_arr_competency));
             }
//             else 
//             {
////                 $sql = $sql."where v_amendment. ".$in_query;
//                 $sql = $sql.$this->db->where('v_amendment',$in_query);
//             }
         }
//         $sql = $this->db->get();
        $sql = $this->db->get();
         return $sql;
         }
   
      private static function isfilterset($fp_filtervalue)
   {
       $filter_set = false;
       if(!($fp_filtervalue == ''|| $fp_filtervalue == null)){
            $filter_set = true;
       }
       return $filter_set;
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
 
// Approve Amendments.
    public function approveammendments($fp_arr_emp_id, $fp_arr_comments,$fp_arr_stat) {
       $value = self::getAmmendmentsdetails($fp_arr_emp_id);
       $lv_Approved_count = 0;
        $lv_reject_count = 0;
        $lv_res_count = [];
        $lv_count = count($fp_arr_emp_id);
       for ($i = 0; $i < $lv_count; $i++) {
                   if ($fp_arr_stat[$i] == 'Approve'){
                
                self::updateAmmendmentsandmail($value, $fp_arr_comments[$i],$fp_arr_stat[$i]);

                $lv_Approved_count ++;
            } 
            elseif ($fp_arr_stat[$i] == 'Reject'){
                 
                self::updateAmmendmentsandmail($value, $fp_arr_comments[$i],$fp_arr_stat[$i]);
                $lv_reject_count++;
            }  
       }
        
        $lv_res_count['Approved'] = $lv_Approved_count;
        $lv_res_count['Rejected'] = $lv_reject_count;

        return $lv_res_count;
            
    }
    
    public function getAmmendmentsdetails($fp_emp_id)
    {
       $this->db->select(self::C_Star);
       $this->db->from(self::C_M_AMMENDMENT);
       $this->db->where(self::C_ID,$fp_emp_id); 
       
       $query = $this->db->get();
       $result = $query->result_array();
       
       foreach ($result as $key => $value) {
            $re_result = $value;
//            
        }
        return $re_result;
    }
    
   private function updateAmmendmentsandmail($fp_arr_result,$fp_comments,$fp_stat) {
        self::popExistingAmendments();
        
        
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
           

//           $sql = "INSERT INTO `trans_ammendment` (`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`, `proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`,`act`,`roll_off_lead_time`,`ext_notice`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`,`Updated On`)"
//                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate','$lv_act','$lv_roll_off_lead_time','$lv_ext_notice', '$lv_new_sup_corp_id', '$lv_new_sup_id', '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments','$lv_Updated_On')";
           
           $data = array('id' => $lv_id,
                         'name' => $lv_name,
                         'level' =>$lv_level,
                         'IDP' => $lv_IDP,
                         'loc' => $lv_loc,
                         'bill_stat' => $lv_bill_stat,
                         'competency' => $lv_competency,
                         'curr_proj_name' => $lv_curr_proj_name,
                         'curr_sdate' => $lv_curr_sdate,
                         'curr_edate' => $lv_curr_edate,
                         'proj_edate_projected' => $lv_proj_edate_projected,
                         'supervisor' => $lv_supervisor,
                         'cust_name' => $lv_cust_name,
                         'domain_id' => $lv_domain_id,
                         'new_edate' => $lv_new_edate,
                         'act' => $lv_act,
                         'roll_off_lead_time' => $lv_roll_off_lead_time,
                         'ext_notice' => $lv_ext_notice,
                         'new_sup_corp_id' => $lv_new_sup_corp_id,
                         'new_sup_id' => $lv_new_sup_id,
                         'new_sup_name' => $lv_new_sup_name,
                         'reason' => $lv_reason,
                         'req_by' => $lv_req_by,
                         'status' => $lv_status,
                         'ops_comments' => $lv_ops_comments,
                         'Updated_on' => $lv_Updated_On,
                   );
             $this->db->insert('trans_ammendment', $data);
           }
       else 
       {
//          $sql = "UPDATE `trans_ammendment` SET `new_edate`='$lv_new_edate',`new_sup_corp_id`= '$lv_new_sup_corp_id',`new_sup_id`='$lv_new_sup_id',`new_sup_name`='$lv_new_sup_name',`reason`='$lv_reason',`req_by`='$lv_req_by',`status`='$lv_status',`ops_comments`='$lv_ops_comments',`Updated On`='$lv_Updated_On' WHERE id = $lv_id"; 
           $upd_data = array( 
               'new_edate'=>$lv_new_edate,
               'new_sup_corp_id'=> $lv_new_sup_corp_id,
               'new_sup_id'=>lv_new_sup_id,
               'new_sup_name'=>$lv_new_sup_name,
               'reason'=>$lv_reason,
               'req_by'=>$lv_req_by,
               'status'=>$lv_status,
               'ops_comments'=>$lv_ops_comments,
               'Updated_On'=>$lv_Updated_On
               
           );
           $this->db->where('id', $lv_id);
           $this->db->update('trans_ammendment', $upd_data);
       }

////         echo $sql;
//        $re_result = cl_DB::updateResultIntoTable($sql);
//        
        
//        $sql_new_edate_adjust = "UPDATE `trans_ammendment` SET `new_edate`='0000-00-00' WHERE `new_edate`='1970-01-01';";    
//        $re_result1 = cl_DB::updateResultIntoTable($sql_new_edate_adjust);
       
       $lv_new_date = '0000-00-00';
       $lv_exist_date = '1970-01-01';
       
      $upd_data1 = array('new_edate' => $lv_new_date,); 

           $this->db->where('new_edate', $lv_exist_date);
            $this->db->update('trans_ammendment', $upd_data1);
        
//         $sql1 = "INSERT INTO `trans_ammendment_history`(`id`, `name`, `level`, `IDP`, `loc`, `bill_stat`, `competency`, `curr_proj_name`, `curr_sdate`, `curr_edate`, `proj_edate_projected`, `supervisor`, `cust_name`, `domain_id`, `new_edate`,`act`,`roll_off_lead_time`,`ext_notice`, `new_sup_corp_id`, `new_sup_id`, `new_sup_name`, `reason`, `req_by`, `status`, `ops_comments`,`Updated On`)"
//                . " VALUES ($lv_id, '$lv_name', '$lv_level', '$lv_IDP', '$lv_loc', '$lv_bill_stat', '$lv_competency', '$lv_curr_proj_name', '$lv_curr_sdate', '$lv_curr_edate', '$lv_proj_edate_projected', '$lv_supervisor', '$lv_cust_name', '$lv_domain_id', '$lv_new_edate','$lv_act','$lv_roll_off_lead_time','$lv_ext_notice', '$lv_new_sup_corp_id', '$lv_new_sup_id', '$lv_new_sup_name', '$lv_reason', '$lv_req_by', '$lv_status', '$lv_ops_comments','$lv_Updated_On')";
          
           
        $ins_data = array('id' => $lv_id,
                         'name' => $lv_name,
                         'level' =>$lv_level,
                         'IDP' => $lv_IDP,
                         'loc' => $lv_loc,
                         'bill_stat' => $lv_bill_stat,
                         'competency' => $lv_competency,
                         'curr_proj_name' => $lv_curr_proj_name,
                         'curr_sdate' => $lv_curr_sdate,
                         'curr_edate' => $lv_curr_edate,
                         'proj_edate_projected' => $lv_proj_edate_projected,
                         'supervisor' => $lv_supervisor,
                         'cust_name' => $lv_cust_name,
                         'domain_id' => $lv_domain_id,
                         'new_edate' => $lv_new_edate,
                         'act' => $lv_act,
                         'roll_off_lead_time' => $lv_roll_off_lead_time,
                         'ext_notice' => $lv_ext_notice,
                         'new_sup_corp_id' => $lv_new_sup_corp_id,
                         'new_sup_id' => $lv_new_sup_id,
                         'new_sup_name' => $lv_new_sup_name,
                         'reason' => $lv_reason,
                         'req_by' => $lv_req_by,
                         'status' => $lv_status,
                         'ops_comments' => $lv_ops_comments,
                         'Updated_on' => $lv_Updated_On,
                   );
            $this->db->insert('trans_ammendment_history', $ins_data);
        
//        $re_result1 = cl_DB::updateResultIntoTable($sql1);
        
        
//        $sql_new_edate_adjust_history = "UPDATE `trans_ammendment_history` SET `new_edate`='0000-00-00' WHERE `new_edate`='1970-01-01';";    
//        $re_result3 = cl_DB::updateResultIntoTable($sql_new_edate_adjust_history);
        $upd_data2 = array('new_edate' => $lv_new_date,); 

           $this->db->where('new_edate', $lv_exist_date);
           $this->db->update('trans_ammendment_history', $upd_data2);
          
          
//         $lo_mail = new cl_NotificationMails();
         
        
//        if ($re_result && $re_result1) {
            if (($lv_nedate == '') && ($lv_new_sup_corp_id != '')) {
                // send mail for  change in supervisor;
              
               $this->m_Notifications->sendTEApproverChangeNotification($fp_arr_result,$fp_stat,$fp_comments);
            } else if ($lv_nedate != '' && $lv_new_sup_corp_id == '') {
                // send mail for  change in enddate;
              
                $this->m_Notifications->sendReleasedateChangeNotification($fp_arr_result,$fp_stat,$fp_comments);
            } elseif (!$lv_nedate == '' && !$lv_new_sup_corp_id == '') {
                // send mail for both change in supervisor and change in end  date
                $this->m_Notifications->sendTEApproverChangeNotification($fp_arr_result,$fp_stat,$fp_comments);
                $this->m_Notifications->sendReleasedateChangeNotification($fp_arr_result,$fp_stat,$fp_comments);
            }
        }
//        return $re_result;
    
    
    public function popExistingAmendments()
    {
       $this->db->select(self::C_ID);
       $this->db->from(self::C_TRANS_AMMENDMENT);
       self::$arr_amendments = $this->db->get();
    }
}

//}
