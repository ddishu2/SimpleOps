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
}
