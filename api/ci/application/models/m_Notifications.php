<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Notifiactions
 *
 * @author dikmishr
 */

require_once(APPPATH.'models/getDetails.php');
class m_Notifications extends CI_model
{
    const   lc_template_path     = 'D:\xampp\htdocs\rmt\mail_templates\\',                
            /**
             * lc_root   Fully qualified filepath in the format
             *           \\\\<IP address>\<directory>\[<directory>]\\
             *            ntbomfs001 -> 10.75.250.149   
             */
            lc_root              = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\Resumes\\',
            lc_colon             = ';',
            lc_capability_config = 'c_capability_config',
            lc_bu_config         = 'c_bu_config',
            gc_so                = 'curr_so',          
            gc_edate             = 'curr_end_date',
            gc_idp               = 'idp',
            gc_sub_bu            = 'sub_bu',
            gc_svc_line          = 'svc_line',
            gc_org               = 'org',
            gc_empid             = 'emp_id',
            gc_emp_name          = 'emp_name',
            gc_prime_skill       = 'prime_skill',
            gc_proj_name         = 'curr_proj_name',
            gc_level             = 'level',
            gc_sup_id            = 'sup_id',
            gc_sup_name          = 'sup_name';

// Private class variables.    
    private $lv_content,
            $lt_emp_details      = [],
            $lt_crd_details      = [],
            $lt_cte_details      = [],
            $lt_act_type         = [],
            $lt_recievers        = [],
            $lt_capability_email = [],
            $lt_corpid_details   = [],
            $lt_pm_details       = [],
            $lt_em_details       = [],
            $lt_req_details      = [],
            $lt_sup_details      = [],
            $lt_hlr_details      = [],
            $lv_query_notifcn,
            $lv_recievers,
            $lv_so_owner,
            $lv_so_number,
            $lv_projname,
            $lv_proj_code,
            $lv_sdate,
            $lv_edate,
            $lv_empname,
            $lv_empid,
            $lv_pri_skill,
            $lv_level,
            $lv_BU,
            $lv_sub_bu,
            $lv_serv_line,
            $lv_location,
            $lv_capability,
            $lv_rel_date,
            $lv_message,
            $lv_headers,
            $lv_subject,
            $lv_so_creator_email,
            $lv_so_creator_name,
            $lv_pm_email,
            $lv_em_email,
            $lv_status,
            $lv_req_by,
            $lv_comments;

// Constructor of the class
    function __construct() 
    {
        $this->load->database();
        $this->lt_capability_email = $this->db->get(self::lc_capability_config)->result_array();
        $this->lt_bu               = $this->db->get(self::lc_bu_config)->result_array();
    }

// Actual methods to be called from other PHP applications.
// 
// Method to send Release date change notification.
    public function sendReleasedateChangeNotification($fp_v_crd, $fp_v_status, $fp_v_comments) {

        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_crd, 'CRD', '', $fp_v_status, '', $fp_v_comments);
        return $lv_return;
    }

// Method to send T&E Approver Change notification.
    public function sendTEApproverChangeNotification($fp_v_cte, $fp_v_status, $fp_v_comments) {

        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_cte, 'CTE', '', $fp_v_status, '', $fp_v_comments);
        return $lv_return;
    }

// Method to send SO Rejection notification.
    public function sendSORejectionNotification($fp_v_so_id, $fp_v_emp_id, $fp_v_trans_id) {
        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_so_id, 'SOR', '', $fp_v_trans_id, $fp_v_emp_id, '');
        return $lv_return;
    }

// Method to send Soft lock release notification.
    public function sendSoftLockReleaseNotification($fp_v_so_id, $fp_v_emp_id, $fp_v_trans_id) {
        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_so_id, 'SLR', '', $fp_v_trans_id, $fp_v_emp_id, '');
        return $lv_return;
    }

// Method to send Hard lock release intimation.
    public function sendhardlockreleasenotification($fp_arr_locks)
    {
        $lv_return = false;
        $lv_return = self::sendnotification($fp_arr_locks, 'RL4', '', '', '', '');
        return $lv_return;
    }
    
// Method to send Soft lock release notification.
    public function sendSoftLockNotification($fp_v_so_id, $i_link, $fp_v_emp_id, $fp_v_trans_id, $fp_v_comments) {
        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_so_id, 'SL', $i_link, $fp_v_trans_id, $fp_v_emp_id, $fp_v_comments);
        return $lv_return;
    }

// Method to send email to someoen if the employee they're approving has already been tagged

    public function sendAlreadyTaggedNotification($i_so_no, $i_emp_id)
    {
        $lv_return = false;
        $lv_return = self::sendnotification($i_so_no, 'ATN', '', '', $i_emp_id, '');
        return $lv_return;
    }
// Function to get the query.
    private function set_query($i_mode) {
        $this->lv_query_notifcn  =  "SELECT * FROM c_notifications_config WHERE action_type = '$i_mode' LIMIT 1";
        $this->lv_query_act_type =  "SELECT * FROM c_act_type_text WHERE action_type = '$i_mode' LIMIT 1";
    }

// Get Email IDs.
    Private function get_emailid($i_reciever) {
// Get row index of capability. 
        foreach ($this->lt_capability_email as $key => $lwa_capability_email) {
            if (( $lwa_capability_email['BU'] === $this->lv_BU ) && ( $lwa_capability_email['capability'] === $this->lv_capability )) {
                $lv_key_cap = $key;
            } elseif (($lwa_capability_email['BU'] === $this->lv_BU ) && ( $lwa_capability_email['capability'] === 'Operations' )) {
                $lv_key_ops = $key;
            }
        }

// Get BU Lead's email-ID.
        foreach ($this->lt_bu as $key => $lwa_bu) {
            if ($lwa_bu['BU'] === $this->lv_BU) {
                $lv_key_bu = $key;
            }
        }

// Get Employee ID/Email IDs of different recievers.                
        switch ($i_reciever) {
            case 'capability_lead':
                return $this->lt_capability_email[$lv_key_cap]['lead'];
                break;
            case 'capability_sub_lead':
                if (array_key_exists($lv_key_cap, $this->lt_capability_email)) {
                    return $this->lt_capability_email[$lv_key_cap]['sub_lead_1'] . self::lc_colon . $this->lt_capability_email[$lv_key_cap]['sub_lead_2'];
                }
                return $this->lt_capability_email[$lv_key_cap]['sub_lead_1'] . self::lc_colon . $this->lt_capability_email[$lv_key_cap]['sub_lead_2'];
                break;
            case 'capability_SPOC':
                if (array_key_exists($lv_key_cap, $this->lt_capability_email)) {
                    return $this->lt_capability_email[$lv_key_cap]['staffing_spoc_1'] . self::lc_colon . $this->lt_capability_email[$lv_key_cap]['staffing_spoc_1'];
                }
                break;
                return ($this->lt_capability_email[$lv_key_cap]['staffing_spoc_1'] . self::lc_colon . $this->lt_capability_email[$lv_key_cap]['staffing_spoc_1']);
                break;
            case 'capability_gen_id':
                if (array_key_exists($lv_key_cap, $this->lt_capability_email)) {
                    return $this->lt_capability_email[$lv_key_cap]['generic_id'];
                }
                break;
            case 'ops_lead':
                return $this->lt_capability_email[$lv_key_ops]['lead'];
                break;
            case 'ops_sub_lead':
                return ($this->lt_capability_email[$lv_key_ops]['sub_lead_1'] . self::lc_colon . $this->lt_capability_email[$lv_key_ops]['sub_lead_2']);
                break;
            case 'ops_gen_id':
                return $this->lt_capability_email[$lv_key_ops]['generic_id'];
                break;
            case 'so_creator':
                return $this->lv_so_creator_email;
                break;
            case 'proj_manager':
                return $this->lv_pm_email;
                break;
            case 'eng_manager':
                return $this->lv_em_email;
                break;
            case 'resource':
                return $this->lv_resource_email;
                break;
            case 'bu_lead':
                if (array_key_exists($lv_key_bu, $this->lt_bu)) {
                    return $this->lt_bu[$lv_key_bu]['lead'];
                }
                break;
            case 'lead_other_bu':
                return $this->lt_capability_email[$lv_key_ops]['generic_id'];
                break;
            case 'crmg':
                return $this->lt_capability_email[$lv_key_ops]['generic_id'];
                break;
            default:
                break;
        }
    }

// Function to get the recievers based on the activity type, employee details etc.            
    Private function get_recievers() {
        if ($this->lt_recievers[0]['capability_lead'] === 'X') {
            $this->lv_recievers .= self::get_emailid('capability_lead') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['capability_sub_lead'] === 'X') {
            $this->lv_recievers .= self::get_emailid('capability_sub_lead') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['capability_SPOC'] === 'X') {
            $this->lv_recievers .= self::get_emailid('capability_SPOC') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['capability_gen_id'] === 'X') {
            $this->lv_recievers .= self::get_emailid('capability_gen_id') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['ops_lead'] === 'X') {
            $this->lv_recievers .= self::get_emailid('ops_lead') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['ops_sub_lead'] === 'X') {
            $this->lv_recievers .= self::get_emailid('ops_sub_lead') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['ops_gen_id'] === 'X') {
            $this->lv_recievers .= self::get_emailid('ops_gen_id') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['so_creator'] === 'X') {
            $this->lv_recievers .= self::get_emailid('so_creator') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['proj_manager'] === 'X') {
            $this->lv_recievers .= self::get_emailid('proj_manager') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['eng_manager'] === 'X') {
            $this->lv_recievers .= self::get_emailid('eng_manager') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['resource'] === 'X') {
            $this->lv_recievers .= self::get_emailid('resource') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['bu_lead'] === 'X') {
            $this->lv_recievers .= self::get_emailid('bu_lead') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['lead_other_bu'] === 'X') {
            $this->lv_recievers .= self::get_emailid('lead_other_bu') . self::lc_colon;
        }
        if ($this->lt_recievers[0]['crmg'] === 'X') {
            $this->lv_recievers .= self::get_emailid('crmg') . self::lc_colon;
        }
    }

// Method to set email headers    
    private function add_header() {
        $lv_uid = md5(uniqid(time()));
        $this->lv_headers  = 'From: appsonesap.in@capgemini.com' . "\r\n";
        $this->lv_headers .= 'Reply-To: appsonesap.in@capgemini.com' . "\r\n";
        $this->lv_headers .= 'cc: appsonesap.in@capgemini.com' . "\r\n";
        $this->lv_headers .= 'MIME-Version: 1.0' . "\r\n";
        $this->lv_headers .= "Content-Type: multipart/mixed; boundary=\"" . $lv_uid . "\"\r\n";
        return $lv_uid;
    }

// Method to add email content. 
    private function add_content($i_uid) {
        $this->lv_message = "--" . $i_uid . "\r\n";
        $this->lv_message .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $this->lv_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $this->lv_message .= $this->lv_content . "\r\n\r\n";
        return $this->lv_message;
    }

// Read the resume to be attached to the email.
    private function Addresume($i_uid) {
        $lv_filepath = self::lc_root . '*' . $this->lv_empid . '*.doc*';
        $lv_fileresult = glob($lv_filepath);
        if ($lv_fileresult) {
            $lv_fileatt_type = 'application/msword'; // File Type
            $this->lv_message .= "--" . $i_uid . "\r\n";
            foreach ($lv_fileresult as $lv_file) {
                $lv_filesize = filesize($lv_file);
                $lv_filename = basename($lv_file);
            }
            $this->lv_message .= "Content-Type: '$lv_fileatt_type'; name=\"" . $lv_filename . "\"\r\n";
            $this->lv_message .= "Content-Transfer-Encoding: base64\r\n";
            $this->lv_message .= "Content-Disposition: attachment\r\n\r\n";
            $this->lv_message .= chunk_split(base64_encode(file_get_contents($lv_file))) . "\r\n";
            $this->lv_message .= "--" . $i_uid . "--";
        }
    }

// Get content based on mode.        
    private function get_content($i_mode) {
        $lv_htmlpath = self::lc_template_path . $i_mode . '.txt';
        $this->lv_content = file_get_contents($lv_htmlpath);
    }

// Get details based on mode.
    private function get_details($i_mode) {
        
// Create object of class CL_GETDETAILS
        $lo_details = new getDetails();

        switch ($i_mode) {
            case 'SL':
// Get SO details            
                $this->lt_so_details = $lo_details->get_so_details($this->lv_so_number);                
                if(array_key_exists(0,$this->lt_so_details))
                {
                $this->lt_corpid_details = $lo_details->get_corpid_details($this->lt_so_details[0]['so_entered_by']);
// Get PM and EM details.            
                $this->lt_pm_details = $lo_details->get_emp_details($this->lt_so_details[0]['pm_id']);
                if (array_key_exists(0, $this->lt_pm_details)) 
                {
                $this->lv_pm_email = $this->lt_pm_details[0]['email'];
                }
                $this->lt_em_details = $lo_details->get_emp_details($this->lt_so_details[0]['em_id']);
                if (array_key_exists(0, $this->lt_em_details)) 
                {
                $this->lv_em_email = $this->lt_em_details[0]['email'];
                }
                }
                if(array_key_exists(0,$this->lt_corpid_details))
                {
                $this->lv_so_creator_email = $this->lt_corpid_details[0]['email'];
                $this->lv_so_creator_name  = $this->lt_corpid_details[0]['emp_name'];
                }
                
// Get employee details.
                $this->lt_emp_details = $lo_details->get_emp_details($this->lv_empid);

// Get details of all capabilities email ids.
                $this->lt_recievers = $this->db->query($this->lv_query_notifcn)->result_array();
                $this->lt_act_type  = $this->db->query($this->lv_query_act_type)->result_array();
                break;

            case 'SLR':
// Get SO details            
                $this->lt_so_details = $lo_details->get_so_details($this->lv_so_number);
                if(array_key_exists(0,$this->lt_so_details))
                {
                $this->lt_corpid_details = $lo_details->get_corpid_details($this->lt_so_details[0]['so_entered_by']);
                if(array_key_exists(0,$this->lt_corpid_details))
                {
                $this->lv_so_creator_email = $this->lt_corpid_details[0]['email'];
                $this->lv_so_creator_name  = $this->lt_corpid_details[0]['emp_name'];
                }
                }

// Get employee details.
                $this->lt_emp_details = $lo_details->get_emp_details($this->lv_empid);

// Get details of all capabilities email ids.
                $this->lt_recievers = $this->db->query($this->lv_query_notifcn)->result_array();
                $this->lt_act_type  = $this->db->query($this->lv_query_act_type)->result_array();
                break;

            case 'SOR':
// Get SO details            
                $this->lt_so_details = $lo_details->get_so_details($this->lv_so_number);
                if(array_key_exists(0,$this->lt_so_details))
                {
                $this->lt_corpid_details = $lo_details->get_corpid_details($this->lt_so_details[0]['so_entered_by']);
                if(array_key_exists(0,$this->lt_corpid_details))
                {
                $this->lv_so_creator_email = $this->lt_corpid_details[0]['email'];
                $this->lv_so_creator_name  = $this->lt_corpid_details[0]['emp_name'];
                }
                }

// Get employee details.
                $this->lt_emp_details = $lo_details->get_emp_details($this->lv_empid);

// Get details of all capabilities email ids.
                $this->lt_recievers = $this->db->query($this->lv_query_notifcn)->result_array();
                $this->lt_act_type  = $this->db->query($this->lv_query_act_type)->result_array();
                break;

            case 'CRD':
// Get employee details.
                $this->lt_emp_details = $lo_details->get_emp_details($this->lt_crd_details['id']);
                
// Get PM details
                if (array_key_exists(0, $this->lt_emp_details))
                {
                $this->lt_pm_details = $lo_details->get_emp_details($this->lt_emp_details[0]['proj_m_id']);
                if (array_key_exists(0, $this->lt_pm_details))
                {
                    $this->lv_pm_email = $this->lt_pm_details[0]['email'];
                }
                }

// Get requested by details               
                $this->lt_req_details = $lo_details->get_corpid_details($this->lt_crd_details['req_by']);

// Get details of all capabilities email ids.
                $this->lt_recievers = $this->db->query($this->lv_query_notifcn)->result_array();
                $this->lt_act_type  = $this->db->query($this->lv_query_act_type)->result_array();
                break;

            case 'CTE':
// Get employee details.
                $this->lt_emp_details = $lo_details->get_emp_details($this->lt_cte_details['id']);
                
// Get PM details
                if (array_key_exists(0, $this->lt_emp_details))
                {
                $this->lt_pm_details = $lo_details->get_emp_details($this->lt_emp_details[0]['proj_m_id']);
                if (array_key_exists(0, $this->lt_pm_details))
                {
                $this->lv_pm_email = $this->lt_pm_details[0]['email'];
                }
                }                

// Get requested by details
                $this->lt_req_details = $lo_details->get_corpid_details($this->lt_cte_details['req_by']);
                $this->lt_sup_details = $lo_details->get_corpid_details($this->lt_cte_details['new_sup_corp_id']);

// Get details of all capabilities email ids.
                $this->lt_recievers = $this->db->query($this->lv_query_notifcn)->result_array();                
                $this->lt_act_type  = $this->db->query($this->lv_query_act_type)->result_array();
                break;
            
            case 'RL4':
// Get details of all capabilities email ids.
                $this->lt_recievers   = $this->db->query($this->lv_query_notifcn)->result_array();
                $this->lt_act_type    = $this->db->query($this->lv_query_act_type)->result_array();
                $this->lt_sup_details = $lo_details->get_emp_details($this->lt_hlr_details[0][self::gc_sup_id], 'm_emp_record');
                break;            
            
            case 'ATN':
// Get details of all capabilities email ids.
                $this->lt_recievers   = $this->db->query($this->lv_query_notifcn)->result_array();
                $this->lt_act_type    = $this->db->query($this->lv_query_act_type)->result_array();

// Get SO details            
                $this->lt_so_details = $lo_details->get_so_details($this->lv_so_number);                
                if(array_key_exists(0,$this->lt_so_details))
                {                
                $this->lt_corpid_details = $lo_details->get_corpid_details($this->lt_so_details[0]['so_entered_by']);
                if(array_key_exists(0,$this->lt_corpid_details))
                {
                $this->lv_so_creator_email = $this->lt_corpid_details[0]['email'];
                $this->lv_so_creator_name  = $this->lt_corpid_details[0]['emp_name'];
                }
                }

// Get employee details.
                $this->lt_emp_details = $lo_details->get_emp_details($this->lv_empid);
                break;
            default:
                break;
        }
    }

// Read details into variables.
    private function read_details($i_mode) {
        switch ($i_mode) {
            case 'SL':
                $this->lv_so_owner = $this->lv_so_creator_name;
                if(array_key_exists(0,$this->lt_so_details))
                {
                $this->lv_projname = $this->lt_so_details[0]['so_proj_name'];
                $this->lv_proj_code = $this->lt_so_details[0]['so_proj_id'];
                $this->lv_sdate = $this->lt_so_details[0]['so_start_date_new'];
                $this->lv_edate = $this->lt_so_details[0]['so_end_date'];
                }
                if(array_key_exists(0,$this->lt_emp_details))
                {
                $this->lv_empname = $this->lt_emp_details[0]['emp_name'];
                $this->lv_empid = $this->lt_emp_details[0]['emp_id'];
                $this->lv_pri_skill = $this->lt_emp_details[0]['skill1_l4'];
                $this->lv_level = $this->lt_emp_details[0]['level'];
                $this->lv_BU = $this->lt_emp_details[0]['idp'];
                $this->lv_sub_bu = $this->lt_emp_details[0]['sub_bu'];
                $this->lv_serv_line = $this->lt_emp_details[0]['svc_line'];
                $this->lv_location = $this->lt_emp_details[0]['org'];
                $this->lv_capability = $this->lt_emp_details[0]['comp'];
                }
                if(array_key_exists(0,$this->lt_act_type))
                {
                $this->lv_subject = $this->lt_act_type[0]['action_type_text'];
                }
                $lv_date = date('d-M-Y');
                $this->lv_rel_date = date('d-M-Y', strtotime($lv_date . ' + 2 days'));
                break;

            case 'SLR':
                $this->lv_so_owner = $this->lv_so_creator_name;
                if(array_key_exists(0,$this->lt_so_details))
                {                
                $this->lv_projname = $this->lt_so_details[0]['so_proj_name'];
                $this->lv_proj_code = $this->lt_so_details[0]['so_proj_id'];
                $this->lv_sdate = $this->lt_so_details[0]['so_sdate'];
                $this->lv_edate = $this->lt_so_details[0]['so_endate'];
                }
                if(array_key_exists(0,$this->lt_emp_details))
                {                
                $this->lv_empname = $this->lt_emp_details[0]['emp_name'];
                $this->lv_empid = $this->lt_emp_details[0]['emp_id'];
                $this->lv_pri_skill = $this->lt_emp_details[0]['skill1_l4'];
                $this->lv_level = $this->lt_emp_details[0]['level'];
                $this->lv_BU = $this->lt_emp_details[0]['idp'];
                $this->lv_sub_bu = $this->lt_emp_details[0]['sub_bu'];
                $this->lv_serv_line = $this->lt_emp_details[0]['svc_line'];
                $this->lv_location = $this->lt_emp_details[0]['org'];
                $this->lv_capability = $this->lt_emp_details[0]['comp'];
                }
                if(array_key_exists(0,$this->lt_act_type))
                {                
                $this->lv_subject = $this->lt_act_type[0]['action_type_text'];
                }
                break;

            case 'SOR':
                $this->lv_so_owner = $this->lv_so_creator_name;
                if(array_key_exists(0,$this->lt_so_details))
                {                  
                $this->lv_projname = $this->lt_so_details[0]['so_proj_name'];
                $this->lv_proj_code = $this->lt_so_details[0]['so_proj_id'];
                $this->lv_sdate = $this->lt_so_details[0]['so_sdate'];
                $this->lv_edate = $this->lt_so_details[0]['so_endate'];
                }
                if(array_key_exists(0,$this->lt_emp_details))
                {                       
                $this->lv_BU = $this->lt_emp_details[0]['idp'];
                $this->lv_capability = $this->lt_emp_details[0]['comp'];
                }
                if(array_key_exists(0,$this->lt_act_type))
                {                    
                $this->lv_subject = $this->lt_act_type[0]['action_type_text'];
                }
                break;

            case 'CRD':
                $this->lv_BU         = $this->lt_crd_details['IDP'];
                $this->lv_capability = $this->lt_crd_details['competency'];
                if(array_key_exists(0,$this->lt_req_details))
                {                   
                $this->lv_req_by     = $this->lt_req_details[0]['emp_name'];
                $this->lv_recievers .= $this->lt_req_details[0]['email'] . self::lc_colon;
                }
                if(array_key_exists(0,$this->lt_act_type))
                {                    
                $this->lv_subject = $this->lt_act_type[0]['action_type_text'];
                }
                break;
                
            case 'CTE':
                $this->lv_BU         = $this->lt_cte_details['IDP'];
                $this->lv_capability = $this->lt_cte_details['competency'];
                if(array_key_exists(0,$this->lt_req_details))
                {                    
                $this->lv_req_by     = $this->lt_req_details[0]['emp_name'];
                $this->lv_recievers .= $this->lt_req_details[0]['email'] . self::lc_colon;
                $this->lv_recievers .= $this->lt_sup_details[0]['email'] . self::lc_colon;
                }
                if(array_key_exists(0,$this->lt_act_type))
                {                    
                $this->lv_subject = $this->lt_act_type[0]['action_type_text'];
                }                
                break;
            
            case 'RL4':
                $this->lv_subject    = $this->lt_act_type[0]['action_type_text'];
                if (array_key_exists(0, $this->lt_sup_details))
                {
                $this->lv_recievers .= $this->lt_sup_details[0]['email'] . self::lc_colon;
                }
                break;
            
            case 'ATN':
                $this->lv_subject    = $this->lt_act_type[0]['action_type_text'];
                $this->lv_so_owner   = $this->lv_so_creator_name;
                if(array_key_exists(0,$this->lt_emp_details))
                {                
                $this->lv_empname = $this->lt_emp_details[0]['emp_name'];
                }
                break;
            default:
                break;
        }
    }

// Parse content with variables.
    private function parse_content($i_mode) {
        switch ($i_mode) {
            case 'SL':
                $this->lv_content = str_replace("GV_SO_OWNER", $this->lv_so_creator_name, $this->lv_content);
                $this->lv_content = str_replace("GV_PROJECT_NAME", $this->lv_projname, $this->lv_content);
                $this->lv_content = str_replace("GV_EMPNAME", $this->lv_empname, $this->lv_content);
                $this->lv_content = str_replace("GV_EMPID", $this->lv_empid, $this->lv_content);
                $this->lv_content = str_replace("GV_PRI_SKILL", $this->lv_pri_skill, $this->lv_content);
                $this->lv_content = str_replace("GV_LEVEL", $this->lv_level, $this->lv_content);
                $this->lv_content = str_replace("GV_PROJECT_CODE", $this->lv_proj_code, $this->lv_content);
                $this->lv_content = str_replace("GV_BU", $this->lv_BU, $this->lv_content);
                $this->lv_content = str_replace("GV_SBU", $this->lv_sub_bu, $this->lv_content);
                $this->lv_content = str_replace("GV_SERV_LINE", $this->lv_serv_line, $this->lv_content);
                $this->lv_content = str_replace("GV_LOCATION", $this->lv_location, $this->lv_content);
                $this->lv_content = str_replace("GV_SO_NO", $this->lv_so_number, $this->lv_content);
                $this->lv_content = str_replace("GV_SDATE", $this->lv_sdate, $this->lv_content);
                $this->lv_content = str_replace("GV_EDATE", $this->lv_edate, $this->lv_content);
                $this->lv_content = str_replace("GV_LINK", $this->lv_link, $this->lv_content);
                $this->lv_content = str_replace("GV_SL_REL_DATE", $this->lv_rel_date, $this->lv_content);
                $this->lv_content = str_replace("GV_RMGC", $this->lv_comments, $this->lv_content);
                break;

            case 'SLR':
                $this->lv_content = str_replace("GV_SO_OWNER", $this->lv_so_creator_name, $this->lv_content);
                $this->lv_content = str_replace("GV_PROJECT_NAME", $this->lv_projname, $this->lv_content);
                $this->lv_content = str_replace("GV_EMPID", $this->lv_empid, $this->lv_content);
                $this->lv_content = str_replace("GV_PRI_SKILL", $this->lv_pri_skill, $this->lv_content);
                $this->lv_content = str_replace("GV_LEVEL", $this->lv_level, $this->lv_content);
                $this->lv_content = str_replace("GV_PROJECT_CODE", $this->lv_proj_code, $this->lv_content);
                $this->lv_content = str_replace("GV_BU", $this->lv_BU, $this->lv_content);
                $this->lv_content = str_replace("GV_SBU", $this->lv_sub_bu, $this->lv_content);
                $this->lv_content = str_replace("GV_SERV_LINE", $this->lv_serv_line, $this->lv_content);
                $this->lv_content = str_replace("GV_LOCATION", $this->lv_location, $this->lv_content);
                $this->lv_content = str_replace("GV_SO_NO", $this->lv_so_number, $this->lv_content);
                $this->lv_content = str_replace("GV_SDATE", $this->lv_sdate, $this->lv_content);
                $this->lv_content = str_replace("GV_EDATE", $this->lv_edate, $this->lv_content);
                $this->lv_content = str_replace("GV_SL_REL_DATE", $this->lv_rel_date, $this->lv_content);
                break;

            case 'SOR':
                $this->lv_content = str_replace("GV_SO_OWNER", $this->lv_so_creator_name, $this->lv_content);
                $this->lv_content = str_replace("GV_PROJECT_NAME", $this->lv_projname, $this->lv_content);
                $this->lv_content = str_replace("GV_PROJECT_CODE", $this->lv_proj_code, $this->lv_content);
                $this->lv_content = str_replace("GV_SO_NO", $this->lv_so_number, $this->lv_content);
                $this->lv_content = str_replace("GV_SDATE", $this->lv_sdate, $this->lv_content);
                $this->lv_content = str_replace("GV_EDATE", $this->lv_edate, $this->lv_content);
                break;

            case 'CRD':
                $this->lv_content = str_replace("GV_UPDATED_BY", $this->lv_req_by, $this->lv_content);
                if($this->lv_status == 'Approve')
                { $this->lv_content = str_replace("GV_STATUS", $this->lv_status . 'd', $this->lv_content); }
                else
                { $this->lv_content = str_replace("GV_STATUS", $this->lv_status . 'ed', $this->lv_content);} 
                $this->lv_content = str_replace("GV_STATUS", $this->lv_status . 'ed', $this->lv_content);
                $this->lv_content = str_replace("GV_ID", $this->lt_crd_details['id'], $this->lv_content);
                $this->lv_content = str_replace("GV_EMPNAME", $this->lt_crd_details['name'], $this->lv_content);
                $this->lv_content = str_replace("GV_COMPETENCY", $this->lt_crd_details['competency'], $this->lv_content);
                $this->lv_content = str_replace("GV_CPN", $this->lt_crd_details['curr_proj_name'], $this->lv_content);
                $this->lv_content = str_replace("GV_CSD", $this->lt_crd_details['curr_sdate'], $this->lv_content);
                $this->lv_content = str_replace("GV_CED", $this->lt_crd_details['curr_edate'], $this->lv_content);
                $this->lv_content = str_replace("GV_PEDP", $this->lt_crd_details['proj_edate_projected'], $this->lv_content);
                $this->lv_supervisor_name = $this->lt_crd_details['supervisor'];

                $this->lv_content = str_replace("GV_SUPERVISOR", $this->lv_supervisor_name, $this->lv_content);
                $this->lv_content = str_replace("GV_NED", $this->lt_crd_details['new_edate'], $this->lv_content);
                $this->lv_content = str_replace("GV_NSID", $this->lt_crd_details['new_sup_corp_id'], $this->lv_content);
                $this->lv_content = str_replace("GV_NSN", $this->lt_crd_details['new_sup_name'], $this->lv_content);
                $this->lv_content = str_replace("GV_RMGC", $this->lv_comments, $this->lv_content);
                break;

            case 'CTE':
                $this->lv_content = str_replace("GV_UPDATED_BY", $this->lv_req_by, $this->lv_content);
                if($this->lv_status == 'Approve')
                { $this->lv_content = str_replace("GV_STATUS", $this->lv_status . 'd', $this->lv_content); }
                else
                { $this->lv_content = str_replace("GV_STATUS", $this->lv_status . 'ed', $this->lv_content); }    
                $this->lv_content = str_replace("GV_ID", $this->lt_cte_details['id'], $this->lv_content);
                $this->lv_content = str_replace("GV_EMPNAME", $this->lt_cte_details['name'], $this->lv_content);
                $this->lv_content = str_replace("GV_COMPETENCY", $this->lt_cte_details['competency'], $this->lv_content);
                $this->lv_content = str_replace("GV_CPN", $this->lt_cte_details['curr_proj_name'], $this->lv_content);
                $this->lv_content = str_replace("GV_CSD", $this->lt_cte_details['curr_sdate'], $this->lv_content);
                $this->lv_content = str_replace("GV_CED", $this->lt_cte_details['curr_edate'], $this->lv_content);
                $this->lv_content = str_replace("GV_PEDP", $this->lt_cte_details['proj_edate_projected'], $this->lv_content);
                $this->lv_content = str_replace("GV_PEDP", $this->lt_cte_details['proj_edate_projected'], $this->lv_content);                
                $this->lv_supervisor_name = $this->lt_cte_details['supervisor'];
                $this->lv_content = str_replace("GV_SUPERVISOR", $this->lv_supervisor_name, $this->lv_content);
                $this->lv_content = str_replace("GV_NED", $this->lt_cte_details['new_edate'], $this->lv_content);
                $this->lv_content = str_replace("GV_NSID", $this->lt_cte_details['new_sup_corp_id'], $this->lv_content);
                $this->lv_content = str_replace("GV_NSN", $this->lt_cte_details['new_sup_name'], $this->lv_content);
                $this->lv_content = str_replace("GV_RMGC", $this->lv_comments, $this->lv_content);
                break;
                
            case 'RL4':
                $this->lv_content = str_replace("GV_PM", $this->lt_hlr_details[0][self::gc_sup_name], $this->lv_content);
                $this->lv_content = str_replace("GV_PROJECT_NAME", $this->lt_hlr_details[0][self::gc_proj_name], $this->lv_content);
                $lv_content = explode('SPLIT_HERE', $this->lv_content);
                $this->lv_content = $lv_content[0];
                foreach ($this->lt_hlr_details as $lv_key_hlr => $lwa_hlr) {
                $this->lv_content .= $lv_content[1];
                $this->lv_content = str_replace("GV_SNO", ($lv_key_hlr + 1), $this->lv_content);
                $this->lv_content = str_replace("GV_SO_NO", $lwa_hlr[self::gc_so], $this->lv_content);
                $this->lv_content = str_replace("GV_EDATE", $lwa_hlr[self::gc_edate], $this->lv_content);
                $this->lv_content = str_replace("GV_BU", $lwa_hlr[self::gc_idp], $this->lv_content);
                $this->lv_content = str_replace("GV_SBU", $lwa_hlr[self::gc_sub_bu], $this->lv_content);
                $this->lv_content = str_replace("GV_SERV_LINE", $lwa_hlr[self::gc_svc_line], $this->lv_content);
                $this->lv_content = str_replace("GV_LOCATION", $lwa_hlr[self::gc_org], $this->lv_content);
                $this->lv_content = str_replace("GV_EMPID", $lwa_hlr[self::gc_empid], $this->lv_content);
                $this->lv_content = str_replace("GV_EMPNAME", $lwa_hlr[self::gc_emp_name], $this->lv_content);
                $this->lv_content = str_replace("GV_PRI_SKILL", $lwa_hlr[self::gc_prime_skill], $this->lv_content);
                $this->lv_content = str_replace("GV_LEVEL", $lwa_hlr[self::gc_level], $this->lv_content);                
                }
                $this->lv_content .= $lv_content[2];
                break;
                
            case 'ATN':
                $this->lv_content = str_replace("GV_SO_OWNER", $this->lv_so_creator_name, $this->lv_content);
                $this->lv_content = str_replace("GV_EMPNAME", $this->lv_empname, $this->lv_content);
                break;
            
            default:                
                break;
        }
    }

// Function to send notifications per SO number.
    private function sendnotification(
    $i_so_number, $i_mode, $i_link = '', $i_transid, $i_emp_id, $i_comments = '') {

//  Set the SO number and empid to global variables.
        if ($i_mode == 'CRD') {
            $this->lt_crd_details = $i_so_number;
            $this->lv_status = $i_transid;
            $this->lv_comments = $i_comments;
        } elseif ($i_mode == 'CTE') {
            $this->lt_cte_details = $i_so_number;
            $this->lv_status = $i_transid;
            $this->lv_comments = $i_comments;
        } elseif ($i_mode == 'RL4') {
            $this->lt_hlr_details = $i_so_number;            
        } else {
            $this->lv_so_number = ltrim($i_so_number, '0');
            $this->lv_empid     = $i_emp_id;
            $this->lv_link      = $i_link;
            $this->lv_comments  = $i_comments;
        }

// Get the email contetnt.
        self::get_content($i_mode);

// Call function to set queries.
        self::set_query($i_mode);

// Call function to get details based on mode
        self::get_details($i_mode);

// Read details into variables.            
        self::read_details($i_mode);

// Set parameters for the email.
        $lv_uid = self::add_header();

// Parse variables into the HTML Content.
        self::parse_content($i_mode);

// Add email header.
        self::add_content($lv_uid);

// Add resume only for soft lock notifications.
        if ($i_mode === 'SL') {
            self::addresume($lv_uid);
        }

// Get recievers for email.                
        self::get_recievers();      
        $this->lv_subject = 'TEST '.$this->lv_subject;
//      $lv_mail = mail($this->lv_recievers, $this->lv_subject, $this->lv_message, $this->lv_headers);              \
        $this->lv_headers = str_replace('cc: appsonesap.in@capgemini.com'."\r\n", '', $this->lv_headers);  
        $lv_mail = mail('dikshant.mishra@capgemini.com;tejas.nakwa@capgemini.com;alice.kolatkar@capgemini.com;praveen.kumaran@capgemini.com;sumit.naik@capgemini.com;aalekh.bhatt@capgemini.com;venkat.karipalli@capgemini.com', $this->lv_subject, $this->lv_message, $this->lv_headers);                     
        if ($lv_mail) 
        { return true; } 
        else 
        { return false; }
    }
}