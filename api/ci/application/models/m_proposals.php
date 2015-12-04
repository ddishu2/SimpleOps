<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class m_proposals extends CI_model
{
    /*
     * change
     */
    public static  $arr_sowithperfectproposal = [];
    public static $arr_sowithnoperfectproposal = [];
    private static $arr_slock_expired = [];
    private static $arr_perfect_proposals = [];
    private static $v_perfect_proposal_count = 0;
    private static $arr_slocked = [];
    private static $arr_slockRejected_Manager = [];
    private static $arr_hlocked = [];
    private static $arr_proposalRejectedByOps = [];
    private $it_deployable_emps = [];
    private $it_multi_prop_allowed_emps = [];
    
     const C_TABNAME1 = 'v_deployable_emps3';
//    const c_emp_skill_fname = 'skill1_l4';
     const c_emp_skill_fname = 'prime_skill';
     
    const C_FNAME_BENCH_AGING = 'bench_aging';
    const c_emp_loc_fname = 'loc';
    const c_emp_level_fname = 'level';
    
    
    
    const C_FNAME_REJECTED = 'rejected';
    const C_DATE_FORMAT    = 'Y-m-d';
    
    
// end change
    
    const C_FNAME_PROPOSAL_ID =  'prop_id';
    const C_FNAME_ITEM_ID     =  'prop_item_id';
    const C_FNAME_SO_ID       =  'so_id';
    const C_FNAME_EMP_ID      = 'emp_id';
    const C_FNAME_SO          = 'so';
    const C_FNAME_EMP         = 'emp';
    const C_FNAME_CREATED_ON  = 'created_on'; 
    const C_TABNAME           =  'trans_proposals';
    //const C_COMMA             = ','.PHP_EOL;
    
    //private $proposal_id;
    //private $item_id;
    
    private $arr_generated_perfect_proposals = [];
    private $lv_prop_id;
    private $arr_open_sos;
    private $arr_deployableEmp;
    private $lo_deployable_emp;
    private $lv_item_id;
    
         public function __construct()
        {
          
               $this->load->database();
//                echo "inside INdex";
        }
        public function set_attributes(m_open_so $fp_o_open_sos, m_BuEmployees $fp_o_deployableEmp)
        {
        $this->lv_prop_id = self::setProposalID();
        
//        $fp_o_open_sos->filterByContainsProjectName($lv_so_projname);
//        $fp_o_open_sos->filterByEqualsProjBU($lv_so_proj_bu);
//        $fp_o_open_sos->filterByInLocationList($larr_so_locs);
//        $fp_o_open_sos->filterByContainsProjectID($fp_v_proj_id);
//        $fp_o_open_sos->filterByEqualsCapability($fp_v_capability);
//        $fp_o_open_sos->filterByContainsCustomerName($fp_v_cust_name);
        $this->arr_open_sos      = $fp_o_open_sos->get();
        $this->lo_deployable_emp = $fp_o_deployableEmp;
        $this->lv_item_id        = 0;
        
        //changes by tejas
        $this->setDeployableEmps();
        $this->getSlocked();
        $this->getSlockedRejectedByManager();
        $this->getHlocked();
        $this->getProposalRejectedByOps();
       // $this->lo_SOEmpSkillMatcher = new cl_SOEmpSkillMatcher();
        //end change
        }
        
        /*
         * function to Set Proposal ID
         */
        private function setProposalID()
        {
//             $lv_query =   'SELECT'.PHP_EOL
//                   . 'MAX(prop_id)'.PHP_EOL
//                   . 'FROM'.PHP_EOL
//                   .self::C_TABNAME.PHP_EOL;
//       $re_PID = cl_DB::getResultsFromQuery($lv_query);
            
           //self::C_FNAME_PROPOSAL_ID 
            $this->db->select_max(self::C_FNAME_PROPOSAL_ID);
            $result = $this->db->get(self::C_TABNAME)->row();  
            $count = $result->prop_id;



//       foreach($re_PID As $key=>$value)
//       {
//        $count = $value['MAX(prop_id)'];
//       }
       $proposal_id = $count +1;
       return $proposal_id;
        }
       
        /*
         * Get Auto Proposal Run
         */
       
        
        public function getAutoProposals() 
        {  
        $lo_emp = $this->lo_deployable_emp ;   
        $re_it_emps_for_sos = [];
        foreach ($this->arr_open_sos as $open_so) 
        {
            $lv_so_id                            = $open_so[m_open_so::C_FNAME_SO_POS_NO];
            $lv_so_skill                         = $open_so[m_open_so::C_FNAME_SKILL];
            $lv_so_loc                           = $open_so[m_open_so::C_FNAME_LOCATION];
            $lv_so_level                         = $open_so[m_open_so::C_FNAME_LEVEL];
            $re_it_emps_for_sos[$lv_so_id][self::C_FNAME_SO] = $open_so;
            $re_it_emps_for_sos[$lv_so_id][self::C_FNAME_PROPOSAL_ID] = $this->lv_prop_id;
            
            if (!self::hasProposalBeenRejectedMaxTimes($lv_so_id))
            {           // find employee for SO only if 
                $lv_emp = $this->getEmpForSO
                    (
                        $lv_so_id, 
                        $lv_so_skill, 
                        $lv_so_level,
                        $lv_so_loc
                    );
//            echo 'Returned' . json_encode($lv_emp);
                if(!is_null($lv_emp)) 
                {
                    $re_it_emps_for_sos[$lv_so_id]['emp'] = $lv_emp;
                }
                else
                {
                    $lv_multi_emp = $this->getMultiProposedEmpForSO
                    (
                        $lv_so_id, 
                        $lv_so_skill, 
                        $lv_so_level,
                        $lv_so_loc
                    );
                    if(!is_null($lv_multi_emp)) 
                {
                    $re_it_emps_for_sos[$lv_so_id]['emp'] = $lv_emp;
                }
                }
            }
        }
        //print_r($re_it_emps_for_sos);
//         $lo_emp->setUnfilledSoAfterPerfectProp($re_it_emps_for_sos);
        self::genrateEmpidSoid($re_it_emps_for_sos); 
        return $re_it_emps_for_sos;
    }
    
    
    
   
   
    private function incrementItemID()
    {
        $this->lv_item_id++;
    }
    
    
    
    
    

public function createProposal( $fp_so_id , $fp_emp_id ) 
    {
      $this->incrementItemID();
      $lv_date = date(self::C_DATE_FORMAT);
//      $lv_prop_id      = cl_abs_QueryBuilder::convertValueToSQLString($this->lv_prop_id);
//      $lv_item_id      = cl_abs_QueryBuilder::convertValueToSQLString($this->lv_item_id);
//      $lv_so_id        = cl_abs_QueryBuilder::convertValueToSQLString($fp_so_id);
//      $lv_emp_id       = cl_abs_QueryBuilder::convertValueToSQLString($fp_emp_id);
//      $lv_created_on   = cl_abs_QueryBuilder::convertValueToSQLString($lv_date);

      $lv_prop_id      = $this->lv_prop_id;
      $lv_item_id      = $this->lv_item_id;
      $lv_so_id        = $fp_so_id;
      $lv_emp_id       = $fp_emp_id;
      $lv_created_on   = $lv_date;
      
      
//       $lv_query = "INSERT INTO `trans_proposals`(`prop_id`, `so_id`, `emp_id` )
//       VALUE('$this->lv_prop_id', '$fp_so_id' , '$fp_emp_id')";    
//       $re_create = cl_DB::postResultIntoTable($lv_query);

//    $lv_query = "INSERT INTO `trans_proposals`(`prop_id`, `so_id`, `emp_id`,`prop_item_id` )
//       VALUE('$this->lv_prop_id', '$fp_so_id' , '$fp_emp_id' , $this->lv_item_id)";    
//       $re_create = cl_DB::postResultIntoTable($lv_query);
     
//       $lv_query = 'INSERT INTO'.PHP_EOL
//                   .self::C_TABNAME.PHP_EOL
//                   .'('.PHP_EOL
//                       .self::C_FNAME_PROPOSAL_ID     .self::C_COMMA
//                       .self::C_FNAME_ITEM_ID     .self::C_COMMA
//                       .self::C_FNAME_SO_ID       .self::C_COMMA
//                       .self::C_FNAME_EMP_ID      .self::C_COMMA
//                       .self::C_FNAME_CREATED_ON  .PHP_EOL
//                   .')'.PHP_EOL
//                   .'VALUE'.PHP_EOL
//                   .'('.PHP_EOL
//                        .$lv_prop_id.self::C_COMMA
//                        .$lv_item_id.self::C_COMMA
//                        .$lv_so_id  .self::C_COMMA
//                        .$lv_emp_id .self::C_COMMA
//                        .$lv_created_on.PHP_EOL
//                   .')'.PHP_EOL;  
//       $re_create_success = cl_DB::postResultIntoTable($lv_query);
//       return $re_create_success ;
       
       
       
       $data = array(
        self::C_FNAME_PROPOSAL_ID => $lv_prop_id,
        self::C_FNAME_ITEM_ID => $lv_item_id,
        self::C_FNAME_SO_ID =>$lv_so_id,
        self::C_FNAME_EMP_ID =>$lv_emp_id,
        self::C_FNAME_CREATED_ON =>$lv_created_on,
        
        );

    return $this->db->insert(self::C_TABNAME, $data);
    }
       /*
        * takes the result of Getautoproposals() and stores into table only the proposals where employee is proposed 
        */ 
      private function genrateEmpidSoid($re_it_emps_for_sos)
       {
           foreach ( $re_it_emps_for_sos as $key => $value)
           {
             if(array_key_exists ('emp',$value))
             {
               
                 if($value['emp'] != null)
                 {
               $lv_empid = $value['emp'][0]['emp_id'];
               $lv_soid = $value['so']['so_pos_no'];
               self::createProposal ($lv_soid,$lv_empid);
                 }
                          
             }
           }
    }
    /**
* Returns true if SO has been rejected MaxTimes 
*@return true|false
     * 
     * @param type $lv_so_id
     * @return boolean|\true|\false
     */
    private static function hasProposalBeenRejectedMaxTimes($lv_so_id)
    { 
        $lock = new m_lock();
        
        $lv_result = false;
        if (
               $lock->getRejectionCount($lv_so_id) >= 3
            )
        {
            $lv_result = true;
        }
        return $lv_result;
    }
    
     
   
  
   
   /*
    * Changes 
    */
   
   
   
   /*
     * Method to set deployable employess and a list of employees that have multiproposals allowed
     */
        protected function setDeployableEmps() {

//        $lt_data = [];
//        $lv_query =  'SELECT * FROM '.self::C_TABNAME1.PHP_EOL
//                    .'ORDER BY '     .self::C_FNAME_BENCH_AGING.' DESC';
//        $lt_data = cl_DB::getResultsFromQuery($lv_query);
////        $this->deployable_emp_count = cl_DB::getCountAndReset();
////        echo $this->deployable_emp_count;
////        if($this->deployable_emp_count > 0)
////        {
//
//        $this->it_deployable_emps = $lt_data;
//        
//        }
         $this->it_deployable_emps = $this->lo_deployable_emp->getUnproposed();
         $this->it_multi_prop_allowed_emps = $this->lo_deployable_emp->getMultiProposable();
         
    }
     /*
     * Method to get All the soft locked Employess
     */
     private function getSlocked() {
        $lt_data = [];
//        $lv_query = "SELECT emp_id FROM trans_locks where status = 'S121'";
//        $lt_data = cl_DB::getResultsFromQuery($lv_query);
          $this->db->select(self::C_FNAME_EMP_ID ); 
          $this->db->from(m_lock::C_TABNAME);   
          $this->db->where(m_lock::C_FNAME_STATUS,m_lock::C_STATUS_SOFT_LOCK );
          $lt_data = $this->db->get()->result_array();

          self::$arr_slocked = $lt_data;

//        }
    }
    /*
     * Method to check if the Employee is Soft locked
     * returns true|false
     */
     public function isSoftLocked($fp_v_emp_id) {

        $lv_slocked = false;
//         echo $fp_v_emp_id."<br>";

        foreach (self::$arr_slocked as $key => $value) {


            if (in_array($fp_v_emp_id, $value)) {
                $lv_slocked = true;
                break;
            }
        }


        $re_slocked = $lv_slocked;
        return $re_slocked;
    }
     /*
     * Gets all employees and SO 's where the manager Rejected the proposed employee
     */
        private function getSlockedRejectedByManager() {
        $lt_data = [];
//        $lv_query = "SELECT * FROM trans_locks where status = 'S221'";
//        $lt_data = cl_DB::getResultsFromQuery($lv_query);
        
           $result = $this->db->get_where(m_lock::C_TABNAME, array(m_lock::C_FNAME_STATUS =>m_lock::C_STATUS_SOFT_LOCK_REJECTED ));             
           $lt_data = $result->result_array();


        self::$arr_slockRejected_Manager = $lt_data;
//        }
        }
/*
 * Checks if the Manager has Rejected this Employee for this SO
 */
    public function isRejectedByManager($fp_v_emp_id, $fp_v_so_no) {

        $lv_isRejectedByManager = false;
//        

        foreach (self::$arr_slockRejected_Manager as $key => $value) {


            if (in_array($fp_v_emp_id, $value)) {
                if ($value[self::C_FNAME_SO_ID ] == $fp_v_so_no) {


                    $lv_isRejectedByManager = true;
                    break;
                }
            }
        }
        $re_isRejectedByManager = $lv_isRejectedByManager;
        return $re_isRejectedByManager;
    }
      
    /*
     Get All Hard Locked Employees
     */
        private function getHlocked() {
        $lt_data = [];
        
//        $lv_query = "SELECT emp_id FROM trans_locks where status = 'S201'";
//        $lt_data = cl_DB::getResultsFromQuery($lv_query);
         $this->db->select(self::C_FNAME_EMP_ID);
      $result = $this->db->get_where(m_lock::C_TABNAME, array(m_lock::C_FNAME_STATUS =>m_lock::C_STATUS_HARD_LOCK ));             
           $lt_data = $result->result_array();


        
        self::$arr_hlocked = $lt_data;




//        }
    }
/*
 * this method  checks whether employee is hardlocked
 */
    public function isHardLocked($fp_v_emp_id) {

        $lv_hlocked = false;
//         echo $fp_v_emp_id."<br>";

        foreach (self::$arr_hlocked as $key => $value) {


            if (in_array($fp_v_emp_id, $value)) {
                $lv_hlocked = true;
                break;
            }
        }


        $re_hlocked = $lv_hlocked;
        return $re_hlocked;
    }
    
        private function getProposalRejectedByOps() {
        $lt_data = [];
//        $lv_query = "SELECT * FROM trans_Proposals where rejected = 'X'";
//        $lt_data = cl_DB::getResultsFromQuery($lv_query);
         $result = $this->db->get_where(self::C_TABNAME, array(self::C_FNAME_REJECTED =>'X' ));             
           $lt_data = $result->result_array();

            self::$arr_proposalRejectedByOps = $lt_data;
    }
/*
 * Checks is the proposal rejected BY Operations Team
 */
    public function isRejectedByOps($fp_v_emp_id, $fp_v_so_no) {

        $lv_isRejectedByOps = false;
        foreach (self::$arr_proposalRejectedByOps as $key => $value) {
          if (in_array($fp_v_emp_id, $value) == true) {

                if ($value[self::C_FNAME_SO_ID] == $fp_v_so_no) {


                    $lv_isRejectedByOps = true;
                    break;
                }
            }
        }
        $re_isRejectedByOps = $lv_isRejectedByOps;
        return $re_isRejectedByOps;
    }
    
    
    
    
    
    
    
 /*
  * fetches a suuitable employee for given SO reuirements 
  */   
    
    public function getEmpForSO($fp_v_so_id, $fp_v_so_skill, $fp_v_so_level, $fp_v_so_loc)
        {

        $lwa_deployable_emp = [];
        $re_wa_emp_for_so = null;
        foreach ($this->it_deployable_emps as $lwa_deployable_emp) {


            $lv_emp_id = $lwa_deployable_emp[self::C_FNAME_EMP_ID];


            $lv_emp_prime_skill = strtolower($lwa_deployable_emp[self::c_emp_skill_fname]);
            // $lv_emp_prime_skill = strtolower($lwa_deployable_emp['prime_skill']);
            $lv_emp_level = strtolower($lwa_deployable_emp[self::c_emp_level_fname]);
            $lv_emp_loc = strtolower($lwa_deployable_emp[self::c_emp_loc_fname]);
//            echo $fp_v_so_id.','
//            .$fp_v_so_skill.','
//            .$fp_v_so_level.','
//            .$fp_v_so_loc.'--->';
//            echo $lwa_deployable_emp['emp_id'].','
//            .$lwa_deployable_emp['skill1_l4'].','
//            .$lwa_deployable_emp['level'].','
//            .$lwa_deployable_emp['org'].PHP_EOL;
//            if ($this->lo_SOEmpSkillMatcher->isMatchOrAlternative($fp_v_so_skill, $lv_emp_prime_skill) && $lv_emp_level == $fp_v_so_level && $lv_emp_loc == $fp_v_so_loc && ($this->isDeployable($lv_emp_id, $fp_v_so_id))
//            )
            if (strtolower($fp_v_so_skill) == $lv_emp_prime_skill && $lv_emp_level == strtolower($fp_v_so_level) && $lv_emp_loc == strtolower($fp_v_so_loc) && ($this->isDeployable($lv_emp_id, $fp_v_so_id))
            )
                            {
                $this->addToPerfectProposal($lv_emp_id, $fp_v_so_id);
                $re_wa_emp_for_so[] = $lwa_deployable_emp;
                /*
                 * check if it is already proposed
                 */
                $arr = $this->m_lock->getDetailsWhereEmpIsAlreadyProposed($lv_emp_id);
                if(!array_key_exists('0', $arr))
               {
                   $re_wa_emp_for_so['ProposedAnyWhereElse'] = 'NO';
              }
              else  
               {
                   $re_wa_emp_for_so['ProposedAnyWhereElse'] = 'Yes';
              }
                 //$re_wa_emp_for_so[] = $lwa_deployable_emp;
//                    echo 'Match'.$fp_v_so_id.'--->'.$lwa_deployable_emp['emp_id'].'======'.json_encode($re_wa_emp_for_so ).PHP_EOL;
                break;
            }
        }

        return $re_wa_emp_for_so;
    }
    
    /*
     * checks if the employee is deployable for taht particular SO 
     * returns true|false
     */
    public function isDeployable($fp_v_emp_id, $fp_v_so_id) {
        $re_isDeployable = false;
        if (!$this->isProposed($fp_v_emp_id) && !$this->isSoftLocked($fp_v_emp_id) && !$this->isRejectedByManager($fp_v_emp_id, $fp_v_so_id) && !$this->isHardLocked($fp_v_emp_id) && !$this->isRejectedByOps($fp_v_emp_id, $fp_v_so_id)&&!$this->isSlockExpired($fp_v_emp_id)) {
            $re_isDeployable = true;
        }
        return $re_isDeployable;
    }

    /*
     * Adds the Employee and SO id to perfect proposals list 
     * so  and Emp can  be filtered later
     */
    
    private function addToPerfectProposal($fp_v_emp_id, $fp_v_so_id) {
        if (!array_key_exists($fp_v_emp_id, self::$arr_perfect_proposals)) {
            self::$arr_perfect_proposals[$fp_v_emp_id] = $fp_v_so_id;
            self::$v_perfect_proposal_count++;
        }
    }

    /**
     * Checks if the Employee is Proposed 
     * @return true|false 
     *
     */
    private function isProposed($fp_v_emp_id) {
        $lv_proposed = false;
        if (array_key_exists($fp_v_emp_id, self::$arr_perfect_proposals)) {
            $lv_proposed = true;
        }
        $re_proposed = $lv_proposed;
        return $re_proposed;
    }
    /*
     * Check for a suitable Employee which is Allowed fro Multiproposal By Ops Team 
     */
      public function getMultiProposedEmpForSO($fp_v_so_id, $fp_v_so_skill, $fp_v_so_level, $fp_v_so_loc)
        {
        $lwa_deployable_emp = [];
        $re_wa_emp_for_so = null;
        foreach ($this->it_multi_prop_allowed_emps as $lwa_deployable_emp) {


            $lv_emp_id = $lwa_deployable_emp[self::C_FNAME_EMP_ID];


            $lv_emp_prime_skill = strtolower($lwa_deployable_emp[self::c_emp_skill_fname]);
            // $lv_emp_prime_skill = strtolower($lwa_deployable_emp['prime_skill']);
            $lv_emp_level = strtolower($lwa_deployable_emp[self::c_emp_level_fname]);
            $lv_emp_loc = strtolower($lwa_deployable_emp[self::c_emp_loc_fname]);
//            echo $fp_v_so_id.','
//            .$fp_v_so_skill.','
//            .$fp_v_so_level.','
//            .$fp_v_so_loc.'--->';
//            echo $lwa_deployable_emp['emp_id'].','
//            .$lwa_deployable_emp['skill1_l4'].','
//            .$lwa_deployable_emp['level'].','
//            .$lwa_deployable_emp['org'].PHP_EOL;
//            if ($this->lo_SOEmpSkillMatcher->isMatchOrAlternative($fp_v_so_skill, $lv_emp_prime_skill) && $lv_emp_level == $fp_v_so_level && $lv_emp_loc == $fp_v_so_loc && ($this->isDeployable($lv_emp_id, $fp_v_so_id))
//            )
            if (strtolower($fp_v_so_skill) == $lv_emp_prime_skill && $lv_emp_level == strtolower($fp_v_so_level) && $lv_emp_loc == strtolower($fp_v_so_loc) && ($this->isMultiProposedDeployable($lv_emp_id, $fp_v_so_id))
            )
                            {
                $this->addToPerfectProposal($lv_emp_id, $fp_v_so_id);
                
               $re_wa_emp_for_so[] = $lwa_deployable_emp;
                $arr = $this->m_lock->getDetailsWhereEmpIsAlreadyProposed($lv_emp_id);
                if(!array_key_exists('0', $arr))
                {
                    $re_wa_emp_for_so['ProposedAnyWhereElse'] = false;
                }
                else  
                {
                    $re_wa_emp_for_so['ProposedAnyWhereElse'] = true;
                }   
                 //$re_wa_emp_for_so[] = $lwa_deployable_emp; 
//                    echo 'Match'.$fp_v_so_id.'--->'.$lwa_deployable_emp['emp_id'].'======'.json_encode($re_wa_emp_for_so ).PHP_EOL;
                break;
            }
        }

        return $re_wa_emp_for_so;
         }

/*
  Even the  same multiproposed Employee should not be re proposed in same run,
 *  he should not be hardlocked
 * nor should he be rejeted by OPs team or manager for that SO 
 */
         public function isMultiProposedDeployable($fp_v_emp_id, $fp_v_so_id) {
        $re_isDeployable = false;
        if (!$this->isProposed($fp_v_emp_id) && !$this->isRejectedByManager($fp_v_emp_id, $fp_v_so_id) && !$this->isHardLocked($fp_v_emp_id) && !$this->isRejectedByOps($fp_v_emp_id, $fp_v_so_id) && !$this->isSlockExpired($fp_v_emp_id)) {
            $re_isDeployable = true;
        }
        return $re_isDeployable;
    }
    
    /*
     * fetch all the slock Expired Employees and filter them in proposals until ops team confirms Expiry 
     
     */
    
     public function getSlockExpired()
     {
         $lt_data = [];
        
//        $lv_query = "SELECT emp_id FROM trans_locks where status = 'S201'";
//        $lt_data = cl_DB::getResultsFromQuery($lv_query);
         $this->db->select(self::C_FNAME_EMP_ID);
      $result = $this->db->get_where(m_lock::C_TABNAME, array(m_lock::C_FNAME_STATUS =>m_lock::C_STATUS_SOFT_LOCK_EXPIRED ));             
           $lt_data = $result->result_array();


        
        self::$arr_slock_expired = $lt_data;
     }
    
     
     public function isSlockExpired($fp_v_emp_id)
     {
          $lv_hlocked = false;
//         echo $fp_v_emp_id."<br>";

        foreach (self::$arr_slock_expired as $key => $value) {


            if (in_array($fp_v_emp_id, $value)) {
                $lv_hlocked = true;
                break;
            }
     }
    
     }
  // end change
     
     
     
     
     // partial proposals method 
     public function getpartialProposals($fp_v_so_id,$fp_v_so_skill, $fp_v_so_level,$fp_v_so_loc)
     {
        
         
        // echo $fp_v_so_id ."     " .$fp_v_so_skill."       ".$fp_v_so_level."     ".$fp_v_so_loc;
//         echo count($this->it_deployable_emps)."</BR>";
          $lwa_deployable_emp = [];
          $re_wa_emps_for_so = null;
        foreach ($this->it_deployable_emps as $lwa_deployable_emp) {


            $lv_emp_id = $lwa_deployable_emp[self::C_FNAME_EMP_ID];


            $lv_emp_prime_skill = strtolower($lwa_deployable_emp[self::c_emp_skill_fname]);
            // $lv_emp_prime_skill = strtolower($lwa_deployable_emp['prime_skill']);
            $lv_emp_level = strtolower($lwa_deployable_emp[self::c_emp_level_fname]);
            $lv_emp_loc = strtolower($lwa_deployable_emp[self::c_emp_loc_fname]);

            
//            if (strtolower($fp_v_so_skill) == $lv_emp_prime_skill && $lv_emp_level == strtolower($fp_v_so_level) && $lv_emp_loc == strtolower($fp_v_so_loc) && ($this->isMultiProposedDeployable($lv_emp_id, $fp_v_so_id))
//            )
//                            {
//                $this->addToPerfectProposal($lv_emp_id, $fp_v_so_id);
//                $re_wa_emp_for_so[] = $lwa_deployable_emp;
////                    echo 'Match'.$fp_v_so_id.'--->'.$lwa_deployable_emp['emp_id'].'======'.json_encode($re_wa_emp_for_so ).PHP_EOL;
//                break;
//            }
            
            $lv_flag_location = $this->isLocationMatching($fp_v_so_loc,$lv_emp_loc);
            $lv_flag_level = $this->isLevelMatching($fp_v_so_level,$lv_emp_level);
            
            $lv_result = ($lv_flag_level || $lv_flag_location);
           // echo "so skill:".strtolower($fp_v_so_skill)."emp_skill".$lv_emp_prime_skill."</br>";
            
            if ($lv_flag_location == true && $lv_flag_level==true)
            {
                $lv_result = false;
            }
            
            
            if (strtolower($fp_v_so_skill) == $lv_emp_prime_skill &&
                     $lv_result &&
                     ($this->isDeployable($lv_emp_id, $fp_v_so_id))
            )
            {
               
                if ($lv_flag_location == false)
                {
                    $lwa_deployable_emp['not_matching'] = 'location';
                }
                else if($lv_flag_level == false)
                {
                    $lwa_deployable_emp['not_matching'] = 'level';
                }   
//                $this->addToPartialProposal($lv_emp_id, $fp_v_so_id);
                $re_wa_emps_for_so[] = $lwa_deployable_emp;
////                   
//                break;
            }
            
            
        }

        return $re_wa_emps_for_so;
     }
     /*
      * returns Boolean true if location is matching
      */
     private function isLocationMatching($fp_v_so_loc,$lv_emp_loc)
     {
         //echo "so Loaction     ".$fp_v_so_loc."     Emp location   ".$lv_emp_loc."</BR>";
         $result = false;
        if(strtolower($lv_emp_loc) == strtolower($fp_v_so_loc))
        {
            $result =  true;
        }
        return $result;
     }
     /*
      * returns Boolean true if level is matching
      */
     private Function isLevelMatching($fp_v_so_level,$lv_emp_level)
     {
          $result = false;
         if ($lv_emp_level == strtolower($fp_v_so_level))
        {
           $result =  true ;
        }
        return $result;
     }
//    private Function addToPartialProposal($lv_emp_id, $fp_v_so_id)
//    {
//        if (!array_key_exists($fp_v_emp_id, self::$arr_partial_proposals)) {
//            self::$arr_partial_proposals[$fp_v_emp_id] = $fp_v_so_id;
//            self::$v_partial_proposal_count++;
//        }
//    }
     
     /*
      * returns all the So's which have no proposed employee from input Array(result of get auto proposal)
      */
    
     
     public function segregateProposals($fp_arr)
     {
      
         foreach ($fp_arr as $key => $value) {
             
             if (!array_key_exists('emp', $value)||$value['emp']== null || $value['emp']=='')
             {
                self::$arr_sowithnoperfectproposal[$key] = $value;
             }
             else 
             {
                self::$arr_sowithperfectproposal[$key] = $value; 
             }
            
         }
         
//         return $arr_no_employee_proposed;
         
     }
     public function getSOWithEmployee()
     {
         return self::$arr_sowithperfectproposal;
     }
     public function getSOWithNoEmployee()
     {
         return self::$arr_sowithnoperfectproposal;
     }
}