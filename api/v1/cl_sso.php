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
        return $lv_cred;
    }
}
