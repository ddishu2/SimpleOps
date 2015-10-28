<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_Auth
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */

require_once __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
class cl_Auth 
{
    const TABNAME = 'users';
    const FNAME_UID = 'user_id';
    const COOKIE_UNAME = 'uname';
    public function authenticate($fp_v_user_id = '', $fp_v_password = '')
    {
        $lv_query = 'SELECT'.PHP_EOL
                    .'user_id'.PHP_EOL
                    .'FROM '.self::TABNAME.PHP_EOL
                    .'WHERE user_id   =  '.$fp_v_user_id. PHP_EOL
                    .'AND   password  =  '.$fp_v_password.PHP_EOL
                    .'LIMIT 1';
        $lv_user =  cl_DB::getResultsFromQuery($lv_query);
        if(!empty($lv_user))
        {
            $cookie_name  =  self::COOKIE_UNAME;
            $cookie_value =  $lv_user['user_id'];
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
        }
        
    }
}
