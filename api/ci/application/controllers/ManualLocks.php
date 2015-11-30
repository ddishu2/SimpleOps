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
          gc_so_pos_no  = 'so_pos_no',  
          gc_to_date    = 'so_to_date',
          gc_proj_bu    = 'proj_bu',
          gc_type       = 'so_type',
          gc_proj_name  = 'proj_name',
          gc_proj_loc   = 'proj_loc',
          gc_proj_id    = 'proj_id',
          gc_cust_name  = 'cust_name',
          gc_capability = 'capability',
          gc_deployable = 'deployable',
          gc_skill      = 'prime_skill',
          gc_level      = 'level',
          gc_lock_soid  = 'so_id',
          gc_lock_empid = 'emp_id',
          gc_lock_sdate = 'lock_start_date',
          gc_lock_edate = 'lock_end_date',
          gc_lock_reqid = 'requestor_id',
          gc_lock_multi = 'allow_multi',
          gc_emp_empid  = 'emp_id',
          gc_emp_corpid = 'domain_id',
          gc_emp_futso  = 'fut_so'; 

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
        $lt_filter_loc = array_filter($lt_proj_loc);
        $lv_proj_id    = $this->input->get(self::gc_proj_id);
        $lv_cust_name  = $this->input->get(self::gc_cust_name);
        $lv_capability = $this->input->get(self::gc_capability);
        $lv_so_no      = $this->input->get(self::gc_so_pos_no);
        $lt_validso = $this->m_ManualLocks->get_ValidSOs(   $lv_so_no,
                                                            $lv_from_date, 
                                                            $lv_to_date, 
                                                            $lv_proj_bu, 
                                                            $lv_type, 
                                                            $lv_proj_name, 
                                                            $lt_filter_loc, 
                                                            $lv_proj_id,
                                                            $lv_cust_name,
                                                            $lv_capability  );
        $this->output->set_content_type('application/json')->set_output(json_encode($lt_validso,JSON_PRETTY_PRINT));      
    }
    
    Public function get_ValidEMPs()
    {
        $lv_deployable = $this->input->get(self::gc_deployable);
        $lv_capability = $this->input->get(self::gc_capability);
        $lv_skill      = $this->input->get(self::gc_skill);
        $lv_location   = array_filter($this->input->get(self::gc_proj_loc));
        $lv_level      = $this->input->get(self::gc_level);
        $lv_empid      = $this->input->get(self::gc_emp_empid);
        $lv_futso      = $this->input->get(self::gc_emp_futso);
        $lt_validemp   = $this->m_ManualLocks->get_ValidEMPs(   $lv_empid,
                                                                $lv_deployable,
                                                                $lv_futso,
                                                                $lv_capability, 
                                                                $lv_skill, 
                                                                $lv_location, 
                                                                $lv_level   );
        $this->output->set_content_type('application/json')->set_output(json_encode($lt_validemp,JSON_PRETTY_PRINT));      
    }
    
    Public function get_ValidTNEs()
    {
        $lv_empid      = $this->input->get(self::gc_emp_empid);
        $lv_corpid     = $this->input->get(self::gc_emp_corpid);
        $lt_validtne   = $this->m_ManualLocks->get_ValidTNEs(   $lv_empid,
                                                                $lv_corpid  );
        $this->output->set_content_type('application/json')->set_output(json_encode($lt_validtne,JSON_PRETTY_PRINT));      
    }   
    
    Public function Lock_EMPs( )
    {
        $lv_so_no  = $this->input->get(self::gc_lock_soid);
        $lv_empid  = $this->input->get(self::gc_lock_empid);
        $lv_sdate  = $this->input->get(self::gc_lock_sdate);
        $lv_edate  = $this->input->get(self::gc_lock_edate);
        $lv_reqid  = $this->input->get(self::gc_lock_reqid);
        $lv_multi  = $this->input->get(self::gc_lock_multi);
        $lv_result = $this->m_ManualLocks->Lock_EMPs(   $lv_so_no, 
                                                        $lv_empid, 
                                                        $lv_sdate, 
                                                        $lv_edate, 
                                                        $lv_multi, 
                                                        $lv_reqid   );
        $this->output->set_content_type('application/json')->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));      
    }
}
