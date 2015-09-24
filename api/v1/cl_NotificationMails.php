<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_NotificationMails
 *
 * @author "Dikshant Mishra dikshant.mishra@capgemini.com"
 */
require __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
require __DIR__.DIRECTORY_SEPARATOR.'cl_get_so_details.php';
class cl_NotificationMails 
    {
    const LC_ROOT = 'C:\xampp\htdocs\\';
    
// Private class variables.    
    private $lv_content,
            $lt_so_details = [],
            $lt_act_type   = [],
            $lt_recievers  = [],
            $lt_capability_email = [],
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
            $key,
            $lwa_result,
            $lv_message,
            $lv_headers;                 

    
// Actual methods to be called from other PHP applications.
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
                                        WHERE action_type = '$i_mode'                                              
                                        ORDER BY action_type"; 
            
            $this->lv_query_capability = "SELECT * 
                                          FROM   m_capability_config";
            $this->lv_query_act_type   = "SELECT * FROM t_act_type_text WHERE action_type = '$i_mode'";
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

// Get row index of capability.         
        foreach ($this->lt_so_details as $key => $lwa_so_details) 
                {                
                if( $lwa_so_details['so_no'] === $this->lv_so_number )
                { $lv_key_so = $key ; } 
                }
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
                return $this->lt_capability_email[$lv_key_ops]['generic_id'];
                break;            
            case 'proj_manager':
                return $this->lt_capability_email[$lv_key_ops]['generic_id'];
                break;
            case 'eng_manager':
                return $this->lt_capability_email[$lv_key_ops]['generic_id'];
                break;
            case 'resource':
                return $this->lt_capability_email[$lv_key_ops]['generic_id'];
                break;
            case 'bu_lead':
                return $this->lt_capability_email[$lv_key_ops]['generic_id'];
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
        foreach ($this->lt_recievers as $key => $lwa_values)
        {
            if ($this->lt_recievers[$key]['capability_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('capability_lead').';'; }
            if ($this->lt_recievers[$key]['capability_sub_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('capability_sub_lead').';'; }
            if ($this->lt_recievers[$key]['capability_SPOC'] === 'X')
            { $this->lv_recievers .= self::get_emailid('capability_SPOC').';'; }
            if ($this->lt_recievers[$key]['capability_gen_id'] === 'X')
            { $this->lv_recievers .= self::get_emailid('capability_gen_id').';'; }
            if ($this->lt_recievers[$key]['ops_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('ops_lead').';'; }    
            if ($this->lt_recievers[$key]['ops_sub_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('ops_sub_lead').';'; }
            if ($this->lt_recievers[$key]['ops_gen_id'] === 'X')
            { $this->lv_recievers .= self::get_emailid('ops_gen_id').';'; }
            if ($this->lt_recievers[$key]['so_creator'] === 'X')
            { $this->lv_recievers .= self::get_emailid('so_creator').';'; } 
            if ($this->lt_recievers[$key]['proj_manager'] === 'X')
            { $this->lv_recievers .= self::get_emailid('proj_manager').';'; }
            if ($this->lt_recievers[$key]['eng_manager'] === 'X')
            { $this->lv_recievers .= self::get_emailid('eng_manager').';'; } 
            if ($this->lt_recievers[$key]['resource'] === 'X')
            { $this->lv_recievers .= self::get_emailid('resource').';'; }  
            if ($this->lt_recievers[$key]['bu_lead'] === 'X')
            { $this->lv_recievers .= self::get_emailid('bu_lead').';'; } 
            if ($this->lt_recievers[$key]['lead_other_bu'] === 'X')
            { $this->lv_recievers .= self::get_emailid('lead_other_bu').';'; } 
            if ($this->lt_recievers[$key]['crmg'] === 'X')
            { $this->lv_recievers .= self::get_emailid('crmg').';'; }             
        }
    }

// Method to set email headers    
    private function add_header()
    {
        $lv_uid            = md5(uniqid(time())); 
        $this->lv_headers  = 'From: postmaster@localhost' . "\r\n";
        $this->lv_headers .= 'Reply-To: postmaster@localhost' . "\r\n";
        $this->lv_headers .= 'bcc: bcc@localhost' . "\r\n"; 
        $this->lv_headers .= 'cc: cc@localhost' . "\r\n";
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
        $lv_filepath    = self::LC_ROOT.'*'.$this->lv_empid.'*.docx';
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
        return $this->lv_message;
            }
        }

// Get content based on mode.        
    private function get_content($i_mode)
    {
        $lv_htmlpath = self::LC_ROOT.$i_mode.'.txt';
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
            $lo_so_details->get_so_details($this->lv_so_number);
            $this->lt_so_details  = $lo_so_details->lt_so_details;
            
// Get employee details.
            $lo_so_details->get_emp_details($this->lv_empid);
            $this->lt_emp_details  = $lo_so_details->lt_emp_details;
            
// Get details of all capabilities email ids.
            $this->lt_recievers = cl_DB::getResultsFromQuery($this->lv_query_notifcn);
            $this->lt_capability_email = cl_DB::getResultsFromQuery($this->lv_query_capability);           
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
                $this->lv_so_owner   = $this->lwa_result['so_owner'];              
                $this->lv_projname   = $this->lwa_result['so_proj_name'];
                $this->lv_proj_code  = $this->lwa_result['so_proj_id'];
                $this->lv_sdate      = $this->lwa_result['so_sdate'];
                $this->lv_edate      = $this->lwa_result['so_endate'];                                
                $this->lv_empname    = $this->lt_emp_details[$this->key]['emp_name'];
                $this->lv_empid      = $this->lt_emp_details[$this->key]['emp_id'];
                $this->lv_pri_skill  = $this->lt_emp_details[$this->key]['skill1_l4'];
                $this->lv_level      = $this->lt_emp_details[$this->key]['level'];
                $this->lv_BU         = $this->lt_emp_details[$this->key]['idp'];
                $this->lv_sub_bu     = $this->lt_emp_details[$this->key]['sub_bu'];
                $this->lv_serv_line  = $this->lt_emp_details[$this->key]['svc_line'];
                $this->lv_location   = $this->lt_emp_details[$this->key]['org'];
                $this->lv_capability = $this->lt_emp_details[$this->key]['comp'];
                $lv_date             = date('d-M-Y');
                $this->lv_rel_date   = date('d-M-Y', strtotime($lv_date. ' + 2 days'));
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
                $this->lv_content  = str_replace("GV_SO_OWNER", $this->lv_so_owner, $this->lv_content);
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
                $this->lv_content  = str_replace("GV_SO_OWNER", $this->lv_so_owner, $this->lv_content);
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
                
// Process the selected tables and format the message to be sent.
            foreach($this->lt_so_details as $this->key => $this->lwa_result)
                {                                                                         
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
                    
                $lv_mail = mail($this->lv_recievers, "E-mail from PHP", $this->lv_message, $this->lv_headers);
                echo('hope this works...<br>');
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
        } 
      
        
      $lo_email = new cl_NotificationMails();
      $lv_email = $lo_email->sendSoftLockNotification(203209, 'http://localhost/rmt/UI/buttons_rmt/WebContent/approval.html', 232, 123546);
      if ($lv_email) 
          {
            echo "YES>>>>";
          }
          