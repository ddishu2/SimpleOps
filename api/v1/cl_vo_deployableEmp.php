<?php


/**
 * Description of cl_vo_emp
 *
 * @author ptellis
 */
class cl_vo_deployableEmp {
//    Employees who have been proposed
//    need to be excluded from subsequent proposals
    private $emp_count = 0;
    private $it_deployable_emps = [];
    private static $arr_perfect_proposals = [];
    private static $v_perfect_proposal_count = 0;
    
    private function setDeployableEmps()
    {
        $lt_data = [];
        $lv_query = "SELECT * FROM `v_deployable_emps` \n"
                    ."ORDER BY hire_date ASC;";
        $lt_data = cl_DB::getResultsFromQuery($lv_query);
        $this->emp_count = $lt_data['count'];
        if($this->emp_count > 0)
        {
            $this->it_deployable_emps = $lt_data['data'];
        }
    }
    
    private function isProposed($fp_v_emp_id)
    {
        $re_proposed = false;
        $lv_proposed = false;
        if(array_key_exists($fp_v_emp_id ,self::$arr_perfect_proposals))
        {
            $lv_proposed = true;
        }
        $re_proposed = $lv_proposed;
        return $re_proposed;
    }
    
    private function addToPerfectProposal($fp_v_emp_id, $fp_v_so_id)
    {
         self::$arr_perfect_proposals[$fp_v_emp_id] = $fp_v_so_id;
         self::$v_perfect_proposal_count++;
    }
    
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
