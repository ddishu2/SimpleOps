<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_NotificationMails
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
require __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
class cl_NotificationMails 
    {
    
// Private class variables.    
    private $lv_so_number,
            $lv_mode,
            $lv_link;
    
// Public array which will be used to export the results fetched.
    public  $lt_so_details = [];
    
    private function set_params(
            $i_so_number,
            $i_mode,
            $i_link)
        { 
        $this->lv_so_number = $i_so_number;
        $this->lv_mode      = $i_mode;
        $this->lv_link      = $i_link;
        }
    
    public function sendSoftLockNotification($fp_v_sl_tans_id)
        {
        //        Sends Mail to SO Ownner to acceot or reject lock given by trans ID
        }
    
        // Function to send notifications per SO number.
    Public function sendnotification(
            $i_so_number,
            $i_mode,
            $i_link)            
        {
        // Function to set parameters to the private variable, so they can be used in 
query.
        $this->set_params($i_so_number, $i_mode, $i_link);
        $lt_so_details    = [];
        $lv_query         = "SELECT * FROM `m_so_rrs` WHERE so_no = '$this->lv_so_number'";
        try 
            {
            $this->lt_so_details  = cl_DB::getResultsFromQuery($lv_query);
            if($this->lt_so_details)
                {
                echo('Processing...<br>');                               
                }
            else
                {   
                echo('Oops! Some error occured<br>');
                }           
            }  
        catch(Exception $e)
            {
            echo $e->getMessage();
            echo '<br>';
            } 
        
// Process the selected tables and format the message to be sent.
        foreach($this->lt_so_details as $key => $lwa_result)
            {
            $lv_value = $lwa_result['so_proj_name'];
            echo 'The project name for SO is '. $lv_value;
            echo'<br>';
            }
        echo $lv_value;
    
// Reading text file which contains HTML string for the email to be read.
        $lv_filename = "http://localhost/email.txt";
        $lv_handle   = fopen($lv_filename, "r");    
        $lv_content  = fread($lv_handle, 4096);
        $lv_content  = str_replace("GV_PROJECT_NAME", $lv_value, $lv_content);
        fclose($lv_handle);
        $lv_headers  = 'MIME-Version: 1.0' . "\r\n";
        $lv_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $lv_mail     =  mail("dishu@localhost", "E-mail from PHP", $lv_content, 
$lv_headers);
        echo('hope this works<br>');
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
    $lo_email = new cl_NotificationMails();
    $lo_email->sendnotification(203209, 'A', '123');
    