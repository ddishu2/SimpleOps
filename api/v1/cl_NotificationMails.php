<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_NotificationMails
 * Sending emails to concerned parties about for different activities.
 * 
 * @author "Dikshant Mishra dikshant.mishra@capgemini.com"
 */
//require __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
require __DIR__.DIRECTORY_SEPARATOR.'cl_get_so_details.php';
class cl_NotificationMails 
    {
    const lc_template_path = 'D:\xampp\htdocs\rmt\mail_templates\\',
/**
* lc_root   Fully qualified filepath in the format
*           \\\\<IP address>\<directory>\[<directory>]\\
*            ntbomfs001 -> 10.75.250.149   
*/
          lc_root          = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\Resumes\\';
    
// Private class variables.    
    private $lv_content,
            $lt_so_details = [],
            $lt_emp_details = [],
            $lt_act_type   = [],
            $lt_recievers  = [],
            $lt_capability_email = [],
            $lt_corpid_details = [],
            $lt_pm_details = [],
            $lt_em_details = [],
            $lv_query_notifcn,
            $lv_query_capability,
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
            $lv_em_email;    
    
// Constructor of the class
   function __construct()
   {    $this->lt_capability_email = [];
        $this->lt_bu               = [];
        $this->lv_query_capability = "SELECT * 
                                          FROM   m_capability_config";
        $this->lv_query_bu         = "SELECT * FROM m_bu_config";
        $this->lt_capability_email = cl_DB::getResultsFromQuery($this->lv_query_capability);
        $this->lt_bu               = cl_DB::getResultsFromQuery($this->lv_query_bu);
   }
// Actual methods to be called from other PHP applications.
// 
// Method to send Release date change notification.
    public function sendReleasedateChangeNotification($fp_v_so_id, $fp_v_emp_id, $fp_v_trans_id)
    {        
        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_so_id, 'CRD', '', $fp_v_trans_id, $fp_v_emp_id);
        return $lv_return;        
    }

// Method to send T&E Approver Change notification.
    public function sendTEApproverChangeNotification($fp_v_so_id, $fp_v_emp_id, $fp_v_trans_id)
    {        
        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_so_id, 'CTE', '', $fp_v_trans_id, $fp_v_emp_id);
        return $lv_return;        
    }
    
// Method to send SO Rejection notification.
    public function sendSORejectionNotification($fp_v_so_id, $fp_v_emp_id, $fp_v_trans_id)
    {        
        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_so_id, 'SOR', '', $fp_v_trans_id, $fp_v_emp_id);
        return $lv_return;        
    }
    
// Method to send Soft lock release notification.
    public function sendSoftLockReleaseNotification($fp_v_so_id, $fp_v_emp_id, $fp_v_trans_id)
    {
        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_so_id, 'SLR', '', $fp_v_trans_id, $fp_v_emp_id);
        return $lv_return;        
    }
    
// Method to send Soft lock release notification.
    public function sendSoftLockNotification($fp_v_so_id, $i_link, $fp_v_emp_id, $fp_v_trans_id)
    {
        $lv_return = false;
        $lv_return = self::sendnotification($fp_v_so_id, 'SL', $i_link, $fp_v_trans_id, $fp_v_emp_id);
        return $lv_return;        
    }   
    
// Function to get the query.
    private function  set_query($i_mode)
            {           
            $this->lv_query_notifcn  = "SELECT *
                                        FROM m_notifications_config
                                        WHERE action_type = '$i_mode' LIMIT 1";                                                                                                               
            $this->lv_query_act_type = "SELECT * FROM t_act_type_text WHERE action_type = '$i_mode' LIMIT 1";
            }            
            
// Get Email IDs.
    Private function get_emailid($i_reciever)
    {   
// Get row index of capability. 
        foreach ($this->lt_capability_email as $key => $lwa_capability_email) 
                {          
                if( ( $lwa_capability_email['BU'] === $this->lv_BU ) && ( $lwa_capability_email['capability'] === $this->lv_capability ) )
                { $lv_key_cap = $key ; } 
                elseif( ($lwa_capability_email['BU'] === $this->lv_BU ) && ( $lwa_capability_email['capability'] === 'Operations' ) )
                { $lv_key_ops = $key ; }    
                }
                
// Get BU Lead's email-ID.
        foreach ($this->lt_bu as $key => $lwa_bu)
                {
                if ( $lwa_bu['BU'] === $this->lv_BU)
                { $lv_key_bu = $key; }
                }
                
// Get Employee ID/Email IDs of different recievers.                
        switch ($i_reciever) 
        {
            case 'capability_lead':               
                return $this->lt_capability_email[$lv_key_cap]['lead'];                                                
                break;
            case 'capability_sub_lead':
                return $this->lt_capability_email[$lv_key_cap]['sub_lead_1'].';'.$this->lt_capability_email[$lv_key_cap]['sub_lead_2'];
                break;
            case 'capability_SPOC':
                return $this->lt_capability_email[$lv_key_cap]['staffing_spoc_1'].';'.$this->lt_capability_email[$lv_key_cap]['staffing_spoc_1'];
                break;    
            case 'capability_gen_id':
                return $this->lt_capability_email[$lv_key_cap]['generic_id'];
                break;    
            case 'ops_lead':
                return $this->lt_capability_email[$lv_key_ops]['lead'];
                break;
            case 'ops_sub_lead':
                return $this->lt_capability_email[$lv_key_ops]['sub_lead_1'].';'.$this->lt_capability_email[$lv_key_ops]['sub_lead_2'];
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
                return $this->lt_bu[$lv_key_bu]['lead'];
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
    Private function get_recievers()
    {        
//        foreach ($this->lt_recievers as $key => $lwa_values)
//        {
            if ($this->lt_recievers[0]['capability_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('capability_lead').';'; }
            if ($this->lt_recievers[0]['capability_sub_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('capability_sub_lead').';'; }
            if ($this->lt_recievers[0]['capability_SPOC'] === 'X')
            { $this->lv_recievers .= self::get_emailid('capability_SPOC').';'; }
            if ($this->lt_recievers[0]['capability_gen_id'] === 'X')
            { $this->lv_recievers .= self::get_emailid('capability_gen_id').';'; }
            if ($this->lt_recievers[0]['ops_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('ops_lead').';'; }    
            if ($this->lt_recievers[0]['ops_sub_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('ops_sub_lead').';'; }
            if ($this->lt_recievers[0]['ops_gen_id'] === 'X')
            { $this->lv_recievers .= self::get_emailid('ops_gen_id').';'; }
            if ($this->lt_recievers[0]['so_creator'] === 'X')
            { $this->lv_recievers .= self::get_emailid('so_creator').';'; } 
            if ($this->lt_recievers[0]['proj_manager'] === 'X')
            { $this->lv_recievers .= self::get_emailid('proj_manager').';'; }
            if ($this->lt_recievers[0]['eng_manager'] === 'X')
            { $this->lv_recievers .= self::get_emailid('eng_manager').';'; } 
            if ($this->lt_recievers[0]['resource'] === 'X')
            { $this->lv_recievers .= self::get_emailid('resource').';'; }  
            if ($this->lt_recievers[0]['bu_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('bu_lead').';'; } 
            if ($this->lt_recievers[0]['lead_other_bu'] === 'X')
            { $this->lv_recievers .= self::get_emailid('lead_other_bu').';'; } 
            if ($this->lt_recievers[0]['crmg'] === 'X')
            { $this->lv_recievers .= self::get_emailid('crmg').';'; }             
//        }
    }

// Method to set email headers    
    private function add_header()
    {
        $lv_uid            = md5(uniqid(time())); 
        $this->lv_headers  = 'From: dikshant.mishra@capgemini.com' . "\r\n";
        $this->lv_headers .= 'Reply-To: dikshant.mishra@capgemini.com' . "\r\n";
//        $this->lv_headers .= 'bcc: tejas.nakwa@capgemini.com' . "\r\n"; 
//        $this->lv_headers .= 'cc: prashanth.tellis@capgemini.com' . "\r\n";
        $this->lv_headers .= 'MIME-Version: 1.0' . "\r\n";
        $this->lv_headers .= "Content-Type: multipart/mixed; boundary=\"".$lv_uid."\"\r\n";
        return $lv_uid;
    }
    
// Method to add email content. 
    private function add_content($i_uid)
        {
        $this->lv_message  = "--".$i_uid."\r\n";
        $this->lv_message .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $this->lv_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $this->lv_message .= $this->lv_content."\r\n\r\n";
        return $this->lv_message;
        }
    
    
// Read the resume to be attached to the email.
    private function Addresume($i_uid)
        {
        $lv_filepath    = self::lc_root.'*'.$this->lv_empid.'*.docx';
        $lv_fileresult  = glob($lv_filepath);            
        if($lv_fileresult)
            {
        $lv_fileatt_type = 'application/msword'; // File Type
        $this->lv_message .= "--".$i_uid."\r\n";
        foreach($lv_fileresult as $lv_file)
            {
            $lv_filesize = filesize($lv_file);
            $lv_filename = basename($lv_file);
            }            
        $this->lv_message .= "Content-Type: '$lv_fileatt_type'; name=\"".$lv_filename."\"\r\n"; 
        $this->lv_message .= "Content-Transfer-Encoding: base64\r\n";
        $this->lv_message .= "Content-Disposition: attachment\r\n\r\n";
        $this->lv_message .=  chunk_split(base64_encode(file_get_contents($lv_file)))."\r\n";
        $this->lv_message .= "--".$i_uid."--"; 
            }
        }

// Get content based on mode.        
    private function get_content($i_mode)
    {
        $lv_htmlpath = self::lc_template_path.$i_mode.'.txt';
        $this->lv_content = file_get_contents($lv_htmlpath);
    }

// Get details based on mode.
    private function get_details($i_mode)
    {       
// Create object of class CL_GET_SO_DETAILS
        $lo_so_details = new cl_get_so_details();
        
        switch ($i_mode) 
        {
            case 'SL':
// Get SO details            
            $this->lt_so_details = $lo_so_details->get_so_details($this->lv_so_number);             
            $this->lt_corpid_details = $lo_so_details->get_corpid_details($this->lt_so_details[0]['so_entered_by']);
            $this->lv_so_creator_email = $this->lt_corpid_details[0]['email'];
            $this->lv_so_creator_name  = $this->lt_corpid_details[0]['emp_name'];
            
// Get employee details.
            $this->lt_emp_details = $lo_so_details->get_emp_details($this->lv_empid);
            
// Get details of all capabilities email ids.
            $this->lt_recievers        = cl_DB::getResultsFromQuery($this->lv_query_notifcn);
            $this->lt_act_type         = cl_DB::getResultsFromQuery($this->lv_query_act_type); 
            
// Get PM and EM details.            
            $this->lt_pm_details       = $lo_so_details->get_emp_details($this->lt_so_details[0]['pm_id']);
            $lv_count = array_count_values($this->lt_pm_details);
            if ($lv_count)
            {   $this->lv_pm_email         = $this->lt_pm_details[0]['email']; }
            else
            {$this->lv_pm_email = 'kishor.ahire@capgemini.com';}
            $this->lt_em_details       = $lo_so_details->get_emp_details($this->lt_so_details[0]['em_id']);
            $lv_count = array_count_values($this->lt_em_details);
            if ($lv_count)
            {  $this->lv_em_email         = $this->lt_em_details[0]['email']; }
            else
            {$this->lv_pm_email = 'kishor.ahire@capgemini.com';}
                break;
            
            case 'SLR':
// Get SO details            
            $this->lt_so_details = $lo_so_details->get_so_details($this->lv_so_number);             
            $this->lt_corpid_details = $lo_so_details->get_corpid_details($this->lt_so_details[0]['so_entered_by']);
            $this->lv_so_creator_email = $this->lt_corpid_details[0]['email'];
            $this->lv_so_creator_name  = $this->lt_corpid_details[0]['emp_name'];
            
            
// Get employee details.
            $this->lt_emp_details = $lo_so_details->get_emp_details($this->lv_empid);
            
// Get details of all capabilities email ids.
            $this->lt_recievers        = cl_DB::getResultsFromQuery($this->lv_query_notifcn);
            $this->lt_act_type         = cl_DB::getResultsFromQuery($this->lv_query_act_type); 
                break;            
            
            case 'SOR':
// Get SO details            
            $this->lt_so_details = $lo_so_details->get_so_details($this->lv_so_number);             
            $this->lt_corpid_details = $lo_so_details->get_corpid_details($this->lt_so_details[0]['so_entered_by']);
            $this->lv_so_creator_email = $this->lt_corpid_details[0]['email'];
            $this->lv_so_creator_name  = $this->lt_corpid_details[0]['emp_name'];
            
// Get employee details.
            $this->lt_emp_details = $lo_so_details->get_emp_details($this->lv_empid);
            
// Get details of all capabilities email ids.
            $this->lt_recievers        = cl_DB::getResultsFromQuery($this->lv_query_notifcn);
            $this->lt_act_type         = cl_DB::getResultsFromQuery($this->lv_query_act_type);                 
                break;
            
            default:
                break;
        }       
    }
    
// Read details into variables.
    private function read_details($i_mode) 
    {
        switch ($i_mode) {
            case 'SL':
                $this->lv_so_owner   = $this->lv_so_creator_name;              
                $this->lv_projname   = $this->lt_so_details[0]['so_proj_name'];
                $this->lv_proj_code  = $this->lt_so_details[0]['so_proj_id'];
                $this->lv_sdate      = $this->lt_so_details[0]['so_sdate'];
                $this->lv_edate      = $this->lt_so_details[0]['so_endate'];                                
                $this->lv_empname    = $this->lt_emp_details[0]['emp_name'];
                $this->lv_empid      = $this->lt_emp_details[0]['emp_id'];
                $this->lv_pri_skill  = $this->lt_emp_details[0]['skill1_l4'];
                $this->lv_level      = $this->lt_emp_details[0]['level'];
                $this->lv_BU         = $this->lt_emp_details[0]['idp'];
                $this->lv_sub_bu     = $this->lt_emp_details[0]['sub_bu'];
                $this->lv_serv_line  = $this->lt_emp_details[0]['svc_line'];
                $this->lv_location   = $this->lt_emp_details[0]['org'];
                $this->lv_capability = $this->lt_emp_details[0]['comp'];
                $this->lv_subject    = $this->lt_act_type[0]['action_type_text'];
                $lv_date             = date('d-M-Y');
                $this->lv_rel_date   = date('d-M-Y', strtotime($lv_date. ' + 2 days'));
                break;

            case 'SLR':
                $this->lv_so_owner   = $this->lv_so_creator_name;              
                $this->lv_projname   = $this->lt_so_details[0]['so_proj_name'];
                $this->lv_proj_code  = $this->lt_so_details[0]['so_proj_id'];
                $this->lv_sdate      = $this->lt_so_details[0]['so_sdate'];
                $this->lv_edate      = $this->lt_so_details[0]['so_endate'];                                
                $this->lv_empname    = $this->lt_emp_details[0]['emp_name'];
                $this->lv_empid      = $this->lt_emp_details[0]['emp_id'];
                $this->lv_pri_skill  = $this->lt_emp_details[0]['skill1_l4'];
                $this->lv_level      = $this->lt_emp_details[0]['level'];
                $this->lv_BU         = $this->lt_emp_details[0]['idp'];
                $this->lv_sub_bu     = $this->lt_emp_details[0]['sub_bu'];
                $this->lv_serv_line  = $this->lt_emp_details[0]['svc_line'];
                $this->lv_location   = $this->lt_emp_details[0]['org'];
                $this->lv_capability = $this->lt_emp_details[0]['comp'];
                $this->lv_subject    = $this->lt_act_type[0]['action_type_text'];
                break;
            
            case 'SOR':
                $this->lv_so_owner   = $this->lv_so_creator_name;              
                $this->lv_projname   = $this->lt_so_details[0]['so_proj_name'];
                $this->lv_proj_code  = $this->lt_so_details[0]['so_proj_id'];
                $this->lv_sdate      = $this->lt_so_details[0]['so_sdate'];
                $this->lv_edate      = $this->lt_so_details[0]['so_endate'];                                
                $this->lv_BU         = $this->lt_emp_details[0]['idp'];                                
                $this->lv_capability = $this->lt_emp_details[0]['comp'];
                $this->lv_subject    = $this->lt_act_type[0]['action_type_text'];
                break;
            default:
                break;            
        }   
    }
    
// Parse content with variables.
    private function parse_content($i_mode) 
    {
        switch ($i_mode) {
            case 'SL':
                $this->lv_content  = str_replace("GV_SO_OWNER", $this->lv_so_creator_name, $this->lv_content);
                $this->lv_content  = str_replace("GV_PROJECT_NAME", $this->lv_projname, $this->lv_content);
                $this->lv_content  = str_replace("GV_EMPNAME", $this->lv_empname, $this->lv_content);
                $this->lv_content  = str_replace("GV_EMPID", $this->lv_empid, $this->lv_content);
                $this->lv_content  = str_replace("GV_PRI_SKILL", $this->lv_pri_skill, $this->lv_content);
                $this->lv_content  = str_replace("GV_LEVEL", $this->lv_level, $this->lv_content); 
                $this->lv_content  = str_replace("GV_PROJECT_CODE", $this->lv_proj_code, $this->lv_content);
                $this->lv_content  = str_replace("GV_BU", $this->lv_BU, $this->lv_content);
                $this->lv_content  = str_replace("GV_SBU", $this->lv_sub_bu, $this->lv_content);
                $this->lv_content  = str_replace("GV_SERV_LINE", $this->lv_serv_line, $this->lv_content);
                $this->lv_content  = str_replace("GV_LOCATION", $this->lv_location, $this->lv_content);
                $this->lv_content  = str_replace("GV_SO_NO", $this->lv_so_number, $this->lv_content);
                $this->lv_content  = str_replace("GV_SDATE", $this->lv_sdate, $this->lv_content);
                $this->lv_content  = str_replace("GV_EDATE", $this->lv_edate, $this->lv_content);
                $this->lv_content  = str_replace("GV_LINK", $this->lv_link, $this->lv_content); 
                $this->lv_content  = str_replace("GV_SL_REL_DATE", $this->lv_rel_date, $this->lv_content); 
                break;
            
            case 'SLR':
                $this->lv_content  = str_replace("GV_SO_OWNER", $this->lv_so_creator_name, $this->lv_content);
                $this->lv_content  = str_replace("GV_PROJECT_NAME", $this->lv_projname, $this->lv_content);
                $this->lv_content  = str_replace("GV_EMPNAME", $this->lv_empname, $this->lv_content);
                $this->lv_content  = str_replace("GV_EMPID", $this->lv_empid, $this->lv_content);
                $this->lv_content  = str_replace("GV_PRI_SKILL", $this->lv_pri_skill, $this->lv_content);
                $this->lv_content  = str_replace("GV_LEVEL", $this->lv_level, $this->lv_content); 
                $this->lv_content  = str_replace("GV_PROJECT_CODE", $this->lv_proj_code, $this->lv_content);
                $this->lv_content  = str_replace("GV_BU", $this->lv_BU, $this->lv_content);
                $this->lv_content  = str_replace("GV_SBU", $this->lv_sub_bu, $this->lv_content);
                $this->lv_content  = str_replace("GV_SERV_LINE", $this->lv_serv_line, $this->lv_content);
                $this->lv_content  = str_replace("GV_LOCATION", $this->lv_location, $this->lv_content);
                $this->lv_content  = str_replace("GV_SO_NO", $this->lv_so_number, $this->lv_content);
                $this->lv_content  = str_replace("GV_SDATE", $this->lv_sdate, $this->lv_content);
                $this->lv_content  = str_replace("GV_EDATE", $this->lv_edate, $this->lv_content);
                $this->lv_content  = str_replace("GV_SL_REL_DATE", $this->lv_rel_date, $this->lv_content);
                break;
            
            case 'SOR':
                $this->lv_content  = str_replace("GV_SO_OWNER", $this->lv_so_creator_name, $this->lv_content);
                $this->lv_content  = str_replace("GV_PROJECT_NAME", $this->lv_projname, $this->lv_content);
                $this->lv_content  = str_replace("GV_PROJECT_CODE", $this->lv_proj_code, $this->lv_content);
                $this->lv_content  = str_replace("GV_SO_NO", $this->lv_so_number, $this->lv_content);
                $this->lv_content  = str_replace("GV_SDATE", $this->lv_sdate, $this->lv_content);
                $this->lv_content  = str_replace("GV_EDATE", $this->lv_edate, $this->lv_content);
                break;
            default:
                break;
        }
    }
    
// Function to send notifications per SO number.
    private function sendnotification(
            $i_so_number,
            $i_mode,
            $i_link = '',
            $i_transid,
            $i_emp_id)            
            {    

//  Set the SO number and empid to global variables.
            $this->lv_so_number = $i_so_number;
            $this->lv_empid     = $i_emp_id;
            $this->lv_link      = $i_link;
        
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
                if ($i_mode === 'SL')
                    { self::addresume($lv_uid); }

// Get recievers for email.                
                self::get_recievers();
                echo $this->lv_recievers;
             //   $lv_mail = mail($this->lv_recievers, $this->lv_subject, $this->lv_message, $this->lv_headers);
                $lv_mail = mail('dikshant.mishra@capgemini.com;tejas.nakwa@capgemini.com', $this->lv_subject, $this->lv_message, $this->lv_headers);
                if($lv_mail)
                    {  
                    return true;                    
                    }
                else
                    {                    
                    return false;
                    }  
            }
        } 

//        $lo_email = new cl_NotificationMails();
//        $lo_email->sendSoftLockReleaseNotification(203209, 232, 132456);
//        $lo_email->sendSoftLockNotification(203209, 'http://localhost/rmt/UI/buttons_rmt/WebContent/approval.html' , 232, 203201);
//        $lo_email->sendSORejectionNotification(203209, 232, 132456);