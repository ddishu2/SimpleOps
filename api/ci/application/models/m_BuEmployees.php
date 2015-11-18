<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class m_BuEmployees extends CI_model
{
     
    const C_UNPROPOSED_TABNAME        = 'v_deployable_emps';
    const C_MULTI_PROPOSED_TABNAME    = 'v_multi_prop_emps';
    const C_FNAME_EMP_ID              = 'emp_id';
    const C_FNAME_EMP_NAME            = 'emp_name';
    const C_FNAME_SERVICE_LINE        = 'emp_svc_line';
    const C_FNAME_LEVEL               = 'emp_level';
    const C_FNAME_SKILL               = 'emp_primary_skill';
    const C_FNAME_REASON              = 'reason';
    const C_FNAME_DEPLOYABLE          = 'deployable';
    const C_FNAME_HIRE_DATE           = 'hire_date';
    const C_FNAME_LOC                 = 'loc';
    const C_FNAME_ORG                 = 'org';
    const C_FNAME_PROJ_ID             = 'curr_proj_code';
    const C_FNAME_PROJ_NAME           = 'curr_proj_name';
    const C_FNAME_START_DATE          = 'curr_start_date';    
    const C_FNAME_FUTURE_SO           = 'curr_end_date';
    const C_FNAME_BILLING_STATUS      = 'fut_so';
    const C_FNAME_SOFT_LOCK_DATE      = 'slock_date';
    const C_FNAME_SOFT_LOCK_END_DATE  = 'slock_exp_date';
    const C_FNAME_BENCH_AGING         = 'bench_aging';
    const C_FNAME_IDP                 = 'idp';
    const C_FNAME_SUBBU               = 'sub_bu';
    
    //const C_FNAME_EMP_ID              = 'emp_id';
    
    
    private $v_from_date,
            $v_to_date;

    public function __construct()
        {
          
               $this->load->database();
//                echo "inside INdex";
        }
    /**
     * 
     * @return array
     */
    public function getUnproposed()
    {
        /**
         * SELECT * FROM v_deployable_emps
         */
       // $lv_query = queryBuilder::selectAll(self::C_UNPROPOSED_TABNAME);
        //$re_unproposed_emps = cl_DB::getResultsFromQuery($lv_query);
        
        $query = $this->db->get(self::C_UNPROPOSED_TABNAME);
        $re_unproposed_emps = $query->result_array();
        
        
        return $re_unproposed_emps;
        
    }
    
    /**
     * 
     * @return array
     */
    public function getMultiProposable()
    {
        /**
         * SELECT * FROM v_multi_prop_emps
         */
//        $lv_query = queryBuilder::selectAll(self::C_MULTI_PROPOSED_TABNAME);
//        $re_multi_proposed_emps = cl_DB::getResultsFromQuery($lv_query);
         $query = $this->db->get(self::C_MULTI_PROPOSED_TABNAME);
         $re_multi_proposed_emps = $query->result_array();
        return $re_multi_proposed_emps;
    }
    
    public function getEmpDetails($fp_v_emp_id)
    {}
}