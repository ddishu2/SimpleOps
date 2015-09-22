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
?>
<?php
require __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
class cl_NotificationMails 
    {
    const LC_ROOT = 'C:\xampp\htdocs\\';
    
// Private class variables.    
    private $lv_content;   
    
// Public array which will be used to export the results fetched.
    public  $lt_so_details = [],
            $lt_act_type   = [];
        
// Method to get the HTML from buffer and reset the buffer.    
    private function get_html($i_mode)
            {
            $lv_htmlpath = self::LC_ROOT.$i_mode.'.txt';
            $this->lv_content = file_get_contents($lv_htmlpath);
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
            $this->get_html($i_mode);
                   
            $lv_query_so         = 'SELECT m_so_rrs.so_no, 
                                           m_so_rrs.so_proj_id, 
                                           m_so_rrs.so_proj_name, 
                                           m_so_rrs.so_sdate, 
                                           m_so_rrs.so_endate 
                                      FROM m_so_rrs 
                                     WHERE so_no ='.$i_so_number.' ORDER BY so_no';
            
            $lv_query_emp        = 'SELECT m_emp_ras.emp_id,
                                           m_emp_ras.emp_name,
                                           m_emp_ras.idp,
                                           m_emp_ras.sub_bu,
                                           m_emp_ras.svc_line,
                                           m_emp_ras.loc
                                      FROM m_emp_ras
                                      WHERE emp_id = '.$i_emp_id.' ORDER BY emp_id';
            
            $lv_query_acttype    = "SELECT * 
                                    FROM t_act_type_text
                                    WHERE action_type = '$i_mode' ORDER BY action_type";

// Get SO details 
            $this->lt_so_details  = cl_DB::getResultsFromQuery($lv_query_so) 
                   or exit('No data found for SO '.$i_so_number); 
// Get employee details.
            $this->lt_emp_details = cl_DB::getResultsFromQuery($lv_query_emp)
                   or exit('No employee found for EMP ID '.$i_emp_id);
                 
// Get Activity type details.
            $this->lt_act_type = cl_DB::getResultsFromQuery($lv_query_acttype)
                   or exit('No data found for activity type '.$i_mode);
            foreach ($this->lt_act_type as $key => $lwa_act_type) 
                {
                $lv_action_text = $lwa_act_type['action_type_text'];
                }
                
// Process the selected tables and format the message to be sent.
            foreach($this->lt_so_details as $key => $lwa_result)
                {
                $lv_projname  = $lwa_result['so_proj_name'];
                $lv_proj_code = $lwa_result['so_proj_id'];
                $lv_sdate     = $lwa_result['so_sdate'];
                $lv_edate     = $lwa_result['so_endate'];
                $lv_empname   = $this->lt_emp_details[$key]['emp_name'];
                $lv_bu        = $this->lt_emp_details[$key]['idp'];
                $lv_sub_bu    = $this->lt_emp_details[$key]['sub_bu'];
                $lv_serv_line = $this->lt_emp_details[$key]['svc_line'];
                $lv_location  = $this->lt_emp_details[$key]['loc'];

                $lv_content  = $this->lv_content;
                $lv_content  = str_replace("GV_PROJECT_NAME", $lv_projname, $lv_content);
                $lv_content  = str_replace("GV_EMPNAME", $lv_empname, $lv_content);
                $lv_content  = str_replace("GV_ACTION_TYPE", $lv_action_text, $lv_content);
                $lv_content  = str_replace("GV_PROJECT_CODE", $lv_proj_code, $lv_content);
                $lv_content  = str_replace("GV_BU", $lv_bu, $lv_content);
                $lv_content  = str_replace("GV_SBU", $lv_sub_bu, $lv_content);
                $lv_content  = str_replace("GV_SERV_LINE", $lv_serv_line, $lv_content);
                $lv_content  = str_replace("GV_LOCATION", $lv_location, $lv_content);
                $lv_content  = str_replace("GV_SDATE", $lv_sdate, $lv_content);
                $lv_content  = str_replace("GV_EDATE", $lv_edate, $lv_content);
                $lv_content  = str_replace("GV_LINK", $i_link, $lv_content); 
            
// Get Resume File.
                $lv_uid         = md5(uniqid(time()));
                $lv_empid       = ltrim($i_emp_id, '0');
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
                    $lv_mail     = mail("dishu@localhost", "E-mail from PHP", $lv_message, $lv_headers);        
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
    $lo_email = new cl_NotificationMails();
    $lo_email->sendnotification(203209, 'SL', 'http://localhost/phpmyadmin', 'abc', 232);
 