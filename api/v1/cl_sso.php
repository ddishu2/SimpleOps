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
        if (count($lv_cred) == 1)
            array_unshift($lv_cred, "(no domain info - perhaps SSPIOmitDomain is On)");
            list($lv_domain, $lv_user) = $lv_cred;
            return $lv_cred;
    }
}
