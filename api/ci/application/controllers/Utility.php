<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utility
 *
 * @author dikmishr
 */
class Utility extends CI_controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('m_utility');
        
    }
    public function get_username()
    {
        $lv_cred = $this->m_utility->get_username();
        echo json_encode($lv_cred, JSON_PRETTY_PRINT);
    }
    
    public function check_hlr()
    {
       $this->m_utility->checkandnotify();       
    }
    public function load_RAS_File()
    {
        $this->m_utility->loadRAS();
    }
    public function load_RRS_File()
    {
        $this->m_utility->loadRAS();
    }
}
