<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_sso
 *
 * @author dikmishr
 */
class cl_sso {
    public function get_username() {
        $lv_cred = explode('\\', $_SERVER['REMOTE_USER']);
        $lv_domain_id = $lv_cred[0];
        $lt_name = cl_DB::getResultsFromQuery("SELECT emp_name FROM m_emp_ras WHERE domain_id = '$lv_cred'");
        foreach ($lt_name as $lwa_name) 
            {
                $lv_name = $lwa_name['emp_name'];
            }
    }
}
