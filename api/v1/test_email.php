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
 $mail =  mail("dishu@localhost", "E-mail from PHP", "Please! Donot read this");
 echo('hope this works');
 if($mail)
 {
 echo('this works');
 }
 else
 {
 echo('this didnt work');
 }