<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Here you get the Auth_User wich comes out to Domain\User
//print_r($_SERVER['PHP_AUTH_USER']);
//echo 'CooG';
//var_dump($_GET);
//echo 'CooP';
//var_dump($_POST);
//echo 'CooR';
//var_dump($_REQUEST);
//echo 'Coo';
//var_dump($_COOKIE);
//var_dump($_SERVER);
$cred = explode('\\',$_SERVER['REMOTE_USER']); 
 if (count($cred) == 1) array_unshift($cred, "(no domain info - perhaps SSPIOmitDomain is On)"); 
 list($domain, $user) = $cred; 

 echo "You appear to be user <B>$user</B><BR/>"; 
 echo "logged into the Windows NT domain <B>$domain</B><BR/>"; 
