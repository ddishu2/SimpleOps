<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    $admin_email = "dishu@localhost";
    $comment = "mail from php";
    $subject = "this email comes from php";
    $email = "postmaster@localhost";
    $filename = "http://localhost/email.txt";
    $handle = fopen($filename, "r");
    //$filesize = filesize($filename);
    $content = fread($handle, 4096);
    fclose($handle);
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 $mail =  mail("dishu@localhost", "E-mail from PHP", $content, $headers);
 echo('hope this works');
 if($mail)
 {
 echo('this works');
 }
 else
 {
 echo('this didnt work');
 }