<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_deployableBUEmp
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */

class cl_deployableBUEmp extends cl_abs_deployableEmp
{
    const c_emp_skill_fname = 'so_loc';
    const c_emp_loc_fname   = 'skill1_l4';
    const c_emp_level_fname = 'level';

    public function __construct()
    {
        $this->setDeployableEmps();
    }
    private function setDeployableEmps()
    {
        $lt_data = [];
        $lv_query = "SELECT * FROM `v_deployable_emps` \n"
                    ."ORDER BY hire_date ASC;";
        $lt_data = cl_DB::getResultsFromQuery($lv_query);
        $this->deployable_emp_count = cl_DB::getCountAndReset();
        if($this->deployable_emp_count > 0)
        {
            $this->it_deployable_emps = $lt_data;
        }
    }
   
    public function get()
    {}
    
    public function isDeployable($fp_v_emp_id)
    {
        
    }
    
    private function isSoftLocked($fp_v_emp_id) {
    }
}
