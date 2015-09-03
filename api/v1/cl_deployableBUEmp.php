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
        
    public function filterBySkills($fp_arr_skills)
    {
//        parent::add
    }
    
    public function filterByLevels($fp_arr_levels)
    {
        
    }
    
    public function filterByLocations($fp_arr_locations)
    {
        
    }
    
    public function getFiltered()
    
//    Returns null if no perfect match found
    public function getEmpForSO($fp_v_so_id,
                                $fp_v_so_skill ,
                                $fp_v_so_level,
                                $fp_v_so_loc
                            )
    {
        $lwa_deployable_emp = [];
        $re_wa_emp_for_so =  null;
        foreach ($this->it_deployable_emps as $lwa_deployable_emp) 
        {
            $lv_emp_id            = $lwa_deployable_emp['emp_id'];
            $lv_emp_prime_skill   = $lwa_deployable_emp['skill1_l4'];
            $lv_emp_level         = $lwa_deployable_emp['level'] ;
            $lv_emp_loc           = $lwa_deployable_emp['org'];
//            echo $fp_v_so_id.','
//            .$fp_v_so_skill.','
//            .$fp_v_so_level.','
//            .$fp_v_so_loc.'--->';
//            echo $lwa_deployable_emp['emp_id'].','
//            .$lwa_deployable_emp['skill1_l4'].','
//            .$lwa_deployable_emp['level'].','
//            .$lwa_deployable_emp['org'].PHP_EOL;
            if( $lv_emp_prime_skill == $fp_v_so_skill
                &&  $lv_emp_level   == $fp_v_so_level 
                &&  $lv_emp_loc     == $fp_v_so_loc     
                && (!$this->isProposed($lv_emp_id))
              )
                {
                    $this->addToPerfectProposal($lv_emp_id, $fp_v_so_id);
                    $re_wa_emp_for_so = $lwa_deployable_emp;
//                    echo 'Match'.$fp_v_so_id.'--->'.$lwa_deployable_emp['emp_id'].'======'.json_encode($re_wa_emp_for_so ).PHP_EOL;
                    break;
                }
            } 
            
            return $re_wa_emp_for_so;
    }
    
    public function __construct()
    {
        $this->setDeployableEmps();
    }
    
    public function validate()
    {
        
    }
    
    public function isDeployable()
    {
        
    }
    
    private function isEmpIDValid()
    {
        
    }
    
    public function amendStartDate()
    {
        
    }
    
    public function amendEndDate()
    {
        
    }
    
    public function amend_T_and_E()
    {
        
    }
    
    public function amend_skill() 
    {
        
    }
}
