<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of m_ManualLocks
 *
 * @author dikmishr
 */

require_once(APPPATH.'models/m_utility.php');
class m_ManualLocks extends CI_model
{   
    const gc_date_format    = 'Y-m-d',
          gc_so_pos_no      = 'so_pos_no',
          gc_so_prim_skill  = 'so_primary_skill',
          gc_so_loc         = 'so_loc',
          gc_so_level       = 'so_level',
          gc_so_sdate_new   = 'so_start_date_new',
          gc_so_edate       = 'so_end_date',
          gc_so_proj_id     = 'so_proj_id',
          gc_so_proj_name   = 'so_proj_name',
          gc_so_proj_bu     = 'so_proj_bu',
          gc_cust_name      = 'cust_name',
          gc_so_capability  = 'so_capability',
          gc_fulfill_stat   = 'm_so_fulfill_stat',
          gc_so_proj_type   = 'so_proj_type',
          gc_tabname        = 'trans_locks',
          gc_so_status      = 'so_status',
          gc_so_status_ne   = 'so_status !=',
          gc_hardlock       = 'S201',
          gc_lock_soid      = 'so_id',
          gc_lock_empid     = 'emp_id',
          gc_lock_sdate     = 'lock_start_date',
          gc_lock_edate     = 'lock_end_date',
          gc_lock_reqid     = 'requestor_id',
          gc_lock_multi     = 'allow_multi',
          gc_lock_transid   = 'trans_id',
          gc_lock_status    = 'status',
          gc_lock_comment   = 'comment',
          gc_lock_spcode    = 'smart_proj_code',
          gc_lock_fte       = 'FTE',
          gc_lock_tagtype   = 'tag_type',
          gc_lock_supid     = 'sup_id',
          gc_lock_supname   = 'sup_name',
          gc_trans_comment  = 'trans_comment',
          gc_manual         = 'manual',
          gc_updated_by     = 'updated_by',
          gc_updated_on     = 'updated_on',
          gc_x              = 'X',
          gc_emp_deploy     = 'deployable',
          gc_emp_futso      = 'fut_so',
          gc_emp_cust       = 'cust_name',
          gc_emp_capab      = 'comp',
          gc_emp_skill      = 'prime_skill',
          gc_emp_level      = 'level',
          gc_emp_curso      = 'curr_so',
          gc_emp_empname    = 'emp_name',
          gc_emp_empid      = 'emp_id',
          gc_emp_loc        = 'loc',
          gc_m_emp_ras_copy = 'm_emp_ras_copy',
          gc_m_emp_rec      = 'm_emp_record',
          gc_emp_corpid     = 'domain_id';  
          
    private $lt_org_arr = [];
    public function __construct()
    {
        $this->load->database();
// Instantiate utility model and use validateDate() to validate the input date format        
        $io_utility = new m_utility();
    }
    
    public function get_ValidSOs(   $i_so_no = '',
                                    $i_from_date = '', 
                                    $i_to_date = '', 
                                    $i_proj_bu = '', 
                                    $i_type = '', 
                                    $i_proj_name = '', 
                                    $i_proj_loc = '', 
                                    $i_proj_id = '',
                                    $i_cust_name = '',
                                    $i_capability = ''  )
    {
        if($this->isFilterset($i_proj_loc))
        {    
        $this->lt_org_arr = $this->normaliseloc($i_proj_loc);
        }
        
// Select SO Number from table        
        $this->db->select(self::gc_so_proj_id.','.self::gc_so_proj_name.','.self::gc_cust_name.','.self::gc_so_proj_bu.','.self::gc_so_pos_no.','.self::gc_so_sdate_new.','.self::gc_so_edate);
        
// Instantiate utility model and use validateDate() to validate the input date format        
        $io_utility = new m_utility();
        
// Check for filters and apply them if they're set.        
        if((($this->isFilterset($i_from_date)) && ($io_utility->validateDate($i_from_date, self::gc_date_format)) === true) &&
           (($this->isFilterset($i_to_date))   && ($io_utility->validateDate($i_to_date, self::gc_date_format)) === true)) 
        {
        $this->db->where(self::gc_so_sdate_new." BETWEEN CAST('$i_from_date' AS DATE)AND CAST('$i_to_date' AS DATE)");
        }
        if($this->isFilterset($i_proj_name))
        {
        $this->db->like(self::gc_so_proj_name, $i_proj_name, 'both');    
        }
        if($this->isFilterset($i_proj_bu))
        {
        $this->db->where(self::gc_so_proj_bu,$i_proj_bu);
        }        
        if($this->isFilterset($this->lt_org_arr))
        {           
        $this->db->where_in(self::gc_so_loc,$this->lt_org_arr);
        }         
        if($this->isFilterset($i_capability))
        {
        $this->db->where(self::gc_so_capability,$i_capability);
        }
        if($this->isFilterset($i_proj_id))
        {
        $this->db->like(self::gc_so_proj_id, $i_proj_id, 'both'); 
        }
        if($this->isFilterset($i_cust_name))
        {
        $this->db->like(self::gc_cust_name, $i_cust_name, 'both'); 
        }
        if($this->isFilterset($i_type))
        {
        $this->db->where(self::gc_so_proj_type,$i_type);
        }
        
// So Number
        if($this->isFilterset($i_so_no))
        {
        $this->db->like(self::gc_so_pos_no, $i_so_no);
        }
        
// Filter out processed SO
        $this->db->where(self::gc_so_status_ne,self::gc_x);        
        
// Once all filters are set, query the view and return the array.          
        return($this->db->get(self::gc_fulfill_stat)->result_array());
    }
    
//  Get Employee Details
    Public function get_ValidEMPs(  $i_empid = '',
                                    $i_deployable = '',
                                    $i_futso = '',
                                    $i_capability = '', 
                                    $i_skill = '', 
                                    $i_location = '', 
                                    $i_level = ''    )
    {        
        
// Select Employee Details from table        
        $this->db->select(self::gc_emp_deploy.','.self::gc_emp_futso.','.self::gc_emp_capab.','.self::gc_emp_skill.','.self::gc_emp_loc.','.self::gc_emp_curso.','.self::gc_emp_empid.','.self::gc_emp_empname.','.self::gc_emp_level);
        
// Instantiate utility model and use validateDate() to validate the input date format        
        $io_utility = new m_utility();
        
// Employee Id        
        if($this->isFilterset($i_empid))
        {
        $this->db->where(self::gc_emp_empid,$i_empid);
        }               
        
// Deployable        
        if($this->isFilterset($i_deployable))
        {
        $this->db->where(self::gc_emp_deploy,$i_deployable);
        }

// Future SO
        if($this->isFilterset($i_futso))
        {
        $this->db->where(self::gc_emp_futso,$i_futso);
        }        

// Capability
        if($this->isFilterset($i_capability))
        {
        $this->db->where(self::gc_emp_capab,$i_capability);
        }
        
// Skill
        if($this->isFilterset($i_skill))
        {
        $this->db->where(self::gc_emp_skill,$i_skill);
        }    
                
// Level
        if($this->isFilterset($i_level))
        {
        $this->db->where(self::gc_emp_level,$i_level);
        }         
        
// Location        
        if($this->isFilterset($i_location))
        {
        $this->db->where_in(self::gc_emp_loc,$i_location);
        }
        
// Once all filters are set, query the view and return the array.            
        return($this->db->get(self::gc_m_emp_ras_copy)->result_array());        
    }
        
// Get T&E Approver details 
    public function get_ValidTNEs(  $i_empid  = '',
                                    $i_corpid = ''  )
    {
// Select Employee Details from table        
        $this->db->select(self::gc_emp_empid.','.self::gc_emp_empname);        
        
// Employee Id        
        if($this->isFilterset($i_empid))
        {
        $this->db->where(self::gc_emp_empid,$i_empid);
        }
        
// Corp Id        
        if($this->isFilterset($i_corpid))
        {
        $this->db->where(self::gc_emp_corpid,$i_corpid);
        }        
        
// Once all filters are set, query the view and return the array.            
        return($this->db->get(self::gc_m_emp_rec)->result_array()); 
    }
    
// Lock Employees to SO    
    public function Lock_EMPs($i_so_no, $i_empid, $i_sdate, $i_edate, $i_multi = '', $i_reqid = '', $i_spc = '', $i_fte = '', $i_tagtype = '', $i_supid = '', $i_supname = '')
    {   
        
// Validate if Employee exists.
        $lv_query = "SELECT ".self::gc_so_pos_no.','.self::gc_so_status." FROM ".self::gc_fulfill_stat." WHERE ".self::gc_so_pos_no." = '$i_so_no' LIMIT 1";
        $lt_so_no = ($this->db->query($lv_query)->result_array());
        if((count($lt_so_no)) > 0)
        {
        if($lt_so_no[0][self::gc_so_status] !== self::gc_x)
        {    
        $lv_so_act = $lt_so_no[0][self::gc_so_pos_no];

// Validate the employee.
        $lv_query_emp = "SELECT emp_id FROM m_emp_ras_copy WHERE emp_id = '$i_empid'";
        $lt_emp       = $this->db->query($lv_query_emp)->result_array();
        if((count($lt_emp)) > 0)
        {    
            
// Instantiate utility model and use validateDate() to validate the input date format        
        $io_utility = new m_utility();
        
// Get max transid in table.        
        $this->db->select_max(self::gc_lock_transid);
        $lv_transid = $this->db->get(self::gc_tabname)->row()->trans_id; 
        
// Transaction 1: Update SO Open view and mark SO as complete        
        $lt_so = [];
        $lt_so = [self::gc_so_status => self::gc_x];   
        $this->db->trans_start();
        $this->db->where(self::gc_so_pos_no, $lv_so_act);
        $this->db->update(self::gc_fulfill_stat, $lt_so);
        if ($this->db->trans_status() === FALSE) 
        {   
        $this->db->trans_rollback();
        }
        else
        {
        $this->db->trans_complete();
        $lv_so_upd = true;
        } 

// Get username
        $lv_cred  = $io_utility->get_username();
        $lv_name  = $lv_cred[0];
        
// Transaction 2: Update Trans_locks table.        
        $lt_translock_data = [];
        $lt_translock_data = [
        self::gc_lock_transid => $lv_transid + 1,
        self::gc_lock_soid    => $i_so_no,
        self::gc_lock_empid   => $i_empid,
        self::gc_lock_status  => self::gc_hardlock,
        self::gc_lock_sdate   => $i_sdate,
        self::gc_lock_edate   => $i_edate,
        self::gc_lock_multi   => $i_multi,            
        self::gc_lock_reqid   => $i_reqid,
        self::gc_updated_by   => $lv_name,
        self::gc_lock_supid   => $i_supid,
        self::gc_lock_supname => $i_supname,
        self::gc_updated_on   => date(self::gc_date_format)
        ];       
        $this->db->trans_start();
        $this->db->set($lt_translock_data);
        $this->db->insert($this->db->dbprefix.self::gc_tabname);
        if ($this->db->trans_status() === FALSE) 
        {   
        $this->db->trans_rollback();
        }
        else
        {
        $this->db->trans_complete();
        $lv_tl_upd = true;
        } 
        
// Transaction 3: Update Trans_Comments table
        $lt_transcomm_data = [];
        $lt_transcomm_data = [
          self::gc_lock_transid => $lv_transid + 1, 
          self::gc_lock_status  => self::gc_hardlock,
          self::gc_lock_comment => self::gc_manual,
          self::gc_lock_spcode  => $i_spc,
          self::gc_lock_fte     => $i_fte,
          self::gc_lock_tagtype => $i_tagtype
        ];
        $this->db->trans_start();
        $this->db->set($lt_transcomm_data);
        $this->db->insert($this->db->dbprefix.self::gc_trans_comment);
        if ($this->db->trans_status() === FALSE) 
        {   
        $this->db->trans_rollback();
        }
        else
        {
        $this->db->trans_complete();
        $lv_tc_upd = true;
        }      
        if($lv_so_upd === true && $lv_tc_upd === true && $lv_tl_upd === true)
        {
        $lv_return = "Tagging Successful";
        }
        else
        {
        $lv_return = "Tagging Failed, Please verify the data";
        }
        return $lv_return;
        }
        else
        {
        $lv_return = "Invalid Employee";
        return $lv_return;
        }
        }
        else
        {
        $lv_return = "SO is already fulfilled, please chose another SO";
        return $lv_return;
        }    
        }
        else
        {
        $lv_return = "Invalid SO";
        return $lv_return;
        }
    }
    private function isFilterset($fp_filter_value)
    {
        $filter_set = false;
        if(!($fp_filter_value == ''|| $fp_filter_value == null)){
            $filter_set = true;
       }
       return $filter_set;
    }
    Private function normaliseloc($i_loc_arr)
    {
        $this->db->select('org');
        $this->db->where_in('loc',$i_loc_arr);
        $lt_arr = $this->db->get('v_locations')->result_array();
        return(array_column($lt_arr,'org'));
    }
}
