<?php


/**
 * Description of cl_vo_emp
 *
 * @author ptellis
 * 
 */
 require __DIR__.DIRECTORY_SEPARATOR.'if_deployableEmps.php';
 abstract class cl_abs_deployableEmp implements if_deployableEmp {
    
    const c_emp_skill_fname = 'so_loc';
    const c_emp_loc_fname   = 'skill1_l4';
    const c_emp_level_fname = 'level';
//    Employees who have been proposed
//    need to be excluded from subsequent proposals
    protected $v_deployable_emp_count = 0;
    protected $it_deployable_emps = [];
    
//    private function setDeployableEmps()
//    {
//        $lt_data = [];
//        $lv_query = "SELECT * FROM `v_deployable_emps` \n"
//                    ."ORDER BY hire_date ASC;";
//        $lt_data = cl_DB::getResultsFromQuery($lv_query);
////        $this->emp_count = $lt_data['count'];
////        if($this->emp_count > 0)
////        {
//            $this->it_deployable_emps = $lt_data;
////        }
//    }
    
    public function __construct()
    {
        $this->setDeployableEmps();
    }
    
   abstract protected function setDeployableEmps();
//    {
//        $this->it_deployable_emps =     [];
//        $this->v_deployable_emp_count = 0;
//        $lv_query = "SELECT * FROM `v_deployable_emps` \n"
//                    ."ORDER BY hire_date ASC;";
//        $lt_data = cl_DB::getResultsFromQuery($lv_query);
//        $this->deployable_emp_count = cl_DB::getCountAndReset();
//        if($this->deployable_emp_count > 0)
//        {
//            $this->it_deployable_emps = $lt_data;
//        }
//    }
        
//    abstract public function filterBySkills($fp_arr_skills);
//    
//    abstract public function filterByLevels($fp_arr_levels);
//    
//    abstract public function filterByLocations($fp_arr_locations);
       
    abstract public function isDeployable();
    abstract public function get();
    abstract protected function isSoftLocked($fp_v_emp_id);
}
