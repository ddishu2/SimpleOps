<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ManualLocks
 *
 * @author dikmishr
 */
class ManualLocks extends CI_Controller
{   
    const gc_from_date  = 'so_from_date',
          gc_to_date    = 'so_to_date',
          gc_proj_bu    = 'proj_bu',
          gc_type       = 'so_type',
          gc_proj_name  = 'proj_name',
          gc_proj_loc   = 'proj_loc',
          gc_proj_id    = 'proj_id',
          gc_cust_name  = 'cust_name',
          gc_capability = 'capability';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_ManualLocks');        
    }    
    
    Public function get_ValidSOs()
    {
        $lv_from_date  = $this->input->get(self::gc_from_date);
        $lv_to_date    = $this->input->get(self::gc_to_date);
        $lv_proj_bu    = $this->input->get(self::gc_proj_bu);
        $lv_type       = $this->input->get(self::gc_type);
        $lv_proj_name  = $this->input->get(self::gc_proj_name);
        $lt_proj_loc   = $this->input->get(self::gc_proj_loc);
        $lv_proj_id    = $this->input->get(self::gc_proj_id);
        $lv_cust_name  = $this->input->get(self::gc_cust_name);
        $lv_capability = $this->input->get(self::gc_capability);
        $lt_validso = $this->m_ManualLocks->get_ValidSOs(   $lv_from_date, 
                                                            $lv_to_date, 
                                                            $lv_proj_bu, 
                                                            $lv_type, 
                                                            $lv_proj_name, 
                                                            $lt_proj_loc, 
                                                            $lv_proj_id,
                                                            $lv_cust_name,
                                                            $lv_capability  );
        $this->output->set_output($lt_proj_loc);
    }
    
    Public function get_ValidEMPs()
    {
        
    }
    
    Public function Lock_EMPs()
    {
        
    }
}
