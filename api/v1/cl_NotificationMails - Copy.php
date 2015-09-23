<?php

/**
 * Description of cl_NotificationMails
 *
 * @author "Dikshant Mishra dikshant.mishra@capgemini.com"
 */
require __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
require __DIR__.DIRECTORY_SEPARATOR.'cl_get_so_details.php';
class cl_NotificationMails 
    {
        const C_TABLE = 'm_notifications_config';
        const LC_ROOT = 'C:\xampp\htdocs\\';
        
        
//        Keep multiple public functions instead of one because:
//        a. Avoid too many parameters
//        b. Avoid Optional parameters
//        YOur code is an interface, it expects certain input, provides expected output
//        Mode violates encapsulation, an outsider needs to understand mode
        
        
    public function __construct()
    {
        $this->setReceivers();
        $this->setMailContent();
    }
    
    public function sendSoftLockNotification($fp_v_so_id, $fp_v_emp_id)
    {
        
        
        
    }
    
    public function sendSoftLockReleaseNotification($fp_v_so_id, $fp_v_emp_id)
    {
        
        
        
    }
    
    public function sendHardLockNotification($fp_v_so_id, $fp_v_emp_id)
    {
        
        
        
    }
    
    public function sendHardLockExpiryReminderMail($fp_v_so_id, $fp_v_emp_id)
    {
        
        
        
    }
    
    
    
// Private class variables.    
    private $lv_content;   
    
    
//    Public attributes violate OOP Encapsulation.
//    Avoid unless absolutely necessary
// Public array which will be used to export the results fetched.
    public  $lt_so_details = [],
            $lt_act_type   = [],
            $lt_recievers  = [],
            $lv_query_notifcn,
            $lv_recievers,
            $lv_capability_lead;
        
// Function to get the HTML from buffer and reset the buffer.    
    private function get_html($i_mode)
    {
        $lv_htmlpath = self::LC_ROOT.$i_mode.'.txt';
        $this->lv_content = file_get_contents($lv_htmlpath);
    }           
 
// Function to get the query.
    private function  set_query($i_mode, $i_capability, $i_bu)
    {
//        Query should not be an attribute! Results of the query can be
//        Don't pass BU and capability to query, fetch entire table and store it 
//        as an attribute in the constructor.
//        Mails will be triggered in loop so you'll be making multiple DB calls
//        
//            $this->lv_query_notifcn  = "SELECT *
//                                        FROM m_notifications_config
//                                        ORDER BY action_type";           
    }
    
// Get Email IDs.
    Private function get_emailid($i_reciever)
    {
        switch ($i_reciever) {
            case $value:


                break;

            default:
                break;
        }   
    }
    
    private function setReceivers()
    {
        $lv_query  = 'SELECT'.PHP_EOL
                    .'*'.PHP_EOL
                    .' FROM '.self::C_TABLE.PHP_EOL
                    .'ORDER BY action_type'.PHP_EOL;  
        $this->lt_recievers = cl_DB::getResultsFromQuery($this->lv_query_notifcn);
    }

// Function to get the recievers based on the activity type, employee details etc.            
    Private function get_recievers()
    {
        $this->lt_recievers = cl_DB::getResultsFromQuery($this->lv_query_notifcn);
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

// Function to send notifications per SO number.
    Public function sendnotification(
            $i_so_number,
            $i_mode,
            $i_link,
            $i_transid,
            $i_emp_id)            
            {
        
// Call function to get HTML of the email.
            self::get_html($i_mode);           
            
// Create object of class CL_GET_SO_DETAILS
            $lo_so_details = new cl_get_so_details();

// Get SO details
            $lo_so_details->get_so_details($i_so_number);
            $this->lt_so_details  = $lo_so_details->lt_so_details;
            
// Get employee details.
            $lo_so_details->get_emp_details($i_emp_id);
            $this->lt_emp_details  = $lo_so_details->lt_emp_details;                 
                
// Process the selected tables and format the message to be sent.
            foreach($this->lt_so_details as $key => $lwa_result)
                {
                $lv_so_owner  = $lwa_result['so_owner'];
                $lv_so_no     = $lwa_result['so_no'];
                $lv_projname  = $lwa_result['so_proj_name'];
                $lv_proj_code = $lwa_result['so_proj_id'];
                $lv_sdate     = $lwa_result['so_sdate'];
                $lv_edate     = $lwa_result['so_endate'];
                $lv_empname   = $this->lt_emp_details[$key]['emp_name'];
                $lv_empid     = $this->lt_emp_details[$key]['emp_id'];
                $lv_pri_skill = $this->lt_emp_details[$key]['prime_skill'];
                $lv_level     = $this->lt_emp_details[$key]['level'];
                $lv_bu        = $this->lt_emp_details[$key]['idp'];
                $lv_sub_bu    = $this->lt_emp_details[$key]['sub_bu'];
                $lv_serv_line = $this->lt_emp_details[$key]['svc_line'];
                $lv_location  = $this->lt_emp_details[$key]['loc'];
                $lv_capability = $this->lt_emp_details[$key]['comp'];
                $lv_date  = date('d-M-Y');
                $lv_rel_date  = date('d-M-Y', strtotime($lv_date. ' + 2 days'));
                
// Call function to set queries.
            self::set_query($i_mode, $lv_capability, $lv_bu); 
            
// Call function to get the recievers.
            self::get_recievers();
                
                $lv_content  = $this->lv_content;
                $lv_content  = str_replace("GV_SO_OWNER", $lv_so_owner, $lv_content);
                $lv_content  = str_replace("GV_PROJECT_NAME", $lv_projname, $lv_content);
                $lv_content  = str_replace("GV_EMPNAME", $lv_empname, $lv_content);
                $lv_content  = str_replace("GV_EMPID", $lv_empid, $lv_content);
                $lv_content  = str_replace("GV_PRI_SKILL", $lv_pri_skill, $lv_content);
                $lv_content  = str_replace("GV_LEVEL", $lv_level, $lv_content); 
                $lv_content  = str_replace("GV_PROJECT_CODE", $lv_proj_code, $lv_content);
                $lv_content  = str_replace("GV_BU", $lv_bu, $lv_content);
                $lv_content  = str_replace("GV_SBU", $lv_sub_bu, $lv_content);
                $lv_content  = str_replace("GV_SERV_LINE", $lv_serv_line, $lv_content);
                $lv_content  = str_replace("GV_LOCATION", $lv_location, $lv_content);
                $lv_content  = str_replace("GV_SO_NO", $lv_so_no, $lv_content);
                $lv_content  = str_replace("GV_SDATE", $lv_sdate, $lv_content);
                $lv_content  = str_replace("GV_EDATE", $lv_edate, $lv_content);
                $lv_content  = str_replace("GV_LINK", $i_link, $lv_content); 
                $lv_content  = str_replace("GV_SL_REL_DATE", $lv_rel_date, $lv_content); 
            
// Get Resume File.
                $lv_uid         = md5(uniqid(time()));
                $lv_filepath    = self::LC_ROOT.'*'.$i_emp_id.'*.docx';
                $lv_fileresult  = glob($lv_filepath);            
                if(!$lv_fileresult)
                    {
                    echo 'Resume not found';
                    }
                else
                    {
                    $lv_fileatt_type = 'application/msword'; // File Type
                    
// Set parameters for the email.             
                    $lv_headers  = 'From: postmaster@localhost' . "\r\n";
                    $lv_headers .= 'Reply-To: postmaster@localhost' . "\r\n";
                    $lv_headers .= 'bcc: bcc@localhost' . "\r\n"; 
                    $lv_headers .= 'cc: cc@localhost' . "\r\n";
                    $lv_headers .= 'MIME-Version: 1.0' . "\r\n";
                    $lv_headers .= "Content-Type: multipart/mixed; boundary=\"".$lv_uid."\"\r\n";
                    $lv_message  = "--".$lv_uid."\r\n";
                    $lv_message .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $lv_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                    $lv_message .= $lv_content."\r\n\r\n";
                    $lv_message .= "--".$lv_uid."\r\n";
                    foreach($lv_fileresult as $lv_file)
                        {
                        $lv_filesize = filesize($lv_file);
                        $lv_filename = basename($lv_file);
                        }            
                    $lv_message .= "Content-Type: '$lv_fileatt_type'; name=\"".$lv_filename."\"\r\n"; 
                    $lv_message .= "Content-Transfer-Encoding: base64\r\n";
                    $lv_message .= "Content-Disposition: attachment\r\n\r\n";
                    $lv_message .=  chunk_split(base64_encode(file_get_contents($lv_file)))."\r\n";
                    $lv_message .= "--".$lv_uid."--";
                    $lv_mail     = mail("dishu@localhost; cc@localhost", "E-mail from PHP", $lv_message, $lv_headers);        
                    echo('hope this works...<br>');
                    if($lv_mail)
                        {   
                        echo('this works<br>');
                        }
                    else
                        {
                        echo('this didnt work');
                        }
                    }   
                }
            } 
        }
 