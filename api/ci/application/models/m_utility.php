<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilities
 *
 * @author Dikshant Mishra/Dikmishr
 */

class m_utility extends CI_model
{
    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_username() 
    {
    $lv_cred = explode('\\', $_SERVER['REMOTE_USER']);
    $lv_domain_id = $lv_cred[0];
    $io_query = $this->db->query("SELECT emp_name FROM m_emp_ras WHERE domain_id = ?", $lv_domain_id);
    $lt_name = $io_query->result_array();
    foreach ($lt_name as $lwa_name) 
        {
            $lv_cred[0] = $lwa_name['emp_name'];
        }
    return $lv_cred;
    }
}
