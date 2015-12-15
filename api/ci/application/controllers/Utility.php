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
//        $this->load->libraries('l_slock_expiry.php');
        
        
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
    
    public function BAfilepath()
    {
        $lv_filepath = $this->m_utility->BAfilepath();
        $this->output->set_content_type('application/json')->set_output(json_encode($lv_filepath,JSON_PRETTY_PRINT));      
    }        
    
    public function load_RAS_File()
    {
        $this->m_utility->loadRAS();
    }
    public function load_FULLFILLSTAT_File()
    {
        $this->m_utility->loadFULLFILLSTAT();
    }
    public function loadAmendments()
    {
        //echo "Hello";
        $this->m_utility->loadAmendments();
    }
    
    public function getslockdetails(){
        $this->m_utility->getslockexpiry();
    }
   
}
