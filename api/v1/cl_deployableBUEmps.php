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
require __DIR__.DIRECTORY_SEPARATOR.'cl_abs_deployableEmps.php';
class cl_deployableBUEmps extends cl_abs_deployableEmp
{
    const c_emp_skill_fname = 'so_loc';
    const c_emp_loc_fname   = 'skill1_l4';
    const c_emp_level_fname = 'level';
    private static $arr_perfect_proposals = [];
    private static $v_perfect_proposal_count = 0;
    private static $arr_slocked = [];
    private static $arr_slockRejected_Manager = []; 
    private static $arr_hlocked = [];
    private static $arr_proposalRejectedByOps = [];
    
    public function __construct()
    {
        $this->setDeployableEmps();
        $this->getSlocked();
        $this->getSlockedRejectedByManager();
        $this->getHlocked();
        $this->getProposalRejectedByOps();
        
    }
    protected function setDeployableEmps()
    {
        
        $lt_data = [];
        $lv_query = "SELECT * FROM v_deployable_emps ORDER BY hire_date ASC";
        $lt_data = cl_DB::getResultsFromQuery($lv_query);
//        $this->deployable_emp_count = cl_DB::getCountAndReset();
//        echo $this->deployable_emp_count;
//        if($this->deployable_emp_count > 0)
//        {
            
            $this->it_deployable_emps = $lt_data;
//        }
    }
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
                && ($this->isDeployable($lv_emp_id,$fp_v_so_id))
              )
                {
                    $this->addToPerfectProposal($lv_emp_id, $fp_v_so_id);
                    $re_wa_emp_for_so[] = $lwa_deployable_emp;
//                    echo 'Match'.$fp_v_so_id.'--->'.$lwa_deployable_emp['emp_id'].'======'.json_encode($re_wa_emp_for_so ).PHP_EOL;
                    break;
                }
            } 
            
            return $re_wa_emp_for_so;
    }
    
   public  function demo()
   {
   }
    public function get()
    {}
    
    public function isDeployable($fp_v_emp_id,$fp_v_so_id)
    {
        $re_isDeployable = false;
        if (!$this->isProposed($fp_v_emp_id)&& !$this->isSoftLocked($fp_v_emp_id) && !$this->isRejectedByManager($fp_v_emp_id,$fp_v_so_id)&& !$this->isHardLocked($fp_v_emp_id)&& !$this->isRejectedByOps($fp_v_emp_id, $fp_v_so_id))
        {
            $re_isDeployable = true;
        }
        return $re_isDeployable;
       
        
    }
    
   
    
    private function addToPerfectProposal($fp_v_emp_id, $fp_v_so_id)
    {
        if(!array_key_exists($fp_v_emp_id, self::$arr_perfect_proposals))
        {
            self::$arr_perfect_proposals[$fp_v_emp_id] = $fp_v_so_id;
            self::$v_perfect_proposal_count++;
        }
    }
    
/**
*@return true|false 
*
*/
    private function isProposed($fp_v_emp_id)
    {
        $lv_proposed = false;
        if(array_key_exists($fp_v_emp_id ,self::$arr_perfect_proposals))
        {
            $lv_proposed = true;
        }
        $re_proposed = $lv_proposed;
        return $re_proposed;
    }
    
     public function isSoftLocked($fp_v_emp_id) {
         
         $lv_slocked = false;
//         echo $fp_v_emp_id."<br>";
         
         foreach (self::$arr_slocked as $key => $value) {
             
         
             if(in_array($fp_v_emp_id,$value))
         {
             $lv_slocked = true;
             break;
         }
       
      }   

        
        $re_slocked = $lv_slocked;
        return $re_slocked;
    }
    
    private function getSlocked()
    {
        $lt_data = [];
       // $lv_query = "SELECT emp_id FROM trans_locks where status = 'S121'";
        $lv_query = "SELECT emp_id FROM trans_locks where status = 'S121'";
    
        
        $lt_data = cl_DB::getResultsFromQuery($lv_query);
//        $this->deployable_emp_count = cl_DB::getCountAndReset();
//        echo $this->deployable_emp_count;
//        if($this->deployable_emp_count > 0)
//        {
//        print_r($lt_data);
            
//           $this->arr_slocked = $lt_data;
             self::$arr_slocked = $lt_data;
             
            
            
            
//        }
            
    }
    private function getSlockedRejectedByManager()
    {
        $lt_data = [];
        $lv_query = "SELECT * FROM trans_locks where status = 'S221'";
        $lt_data = cl_DB::getResultsFromQuery($lv_query);
//        $this->deployable_emp_count = cl_DB::getCountAndReset();
//        echo $this->deployable_emp_count;
//        if($this->deployable_emp_count > 0)
//        {
            
            self::$arr_slockRejected_Manager = $lt_data;
//        }
    
    
    }
     public function isRejectedByManager($fp_v_emp_id,$fp_v_so_no) {
         
         $lv_isRejectedByManager = false;
//        
         
         foreach (self::$arr_slockRejected_Manager as $key => $value) {
             
         
             if(in_array($fp_v_emp_id,$value))
         {
                 if ($value['so_id'] == $fp_v_so_no){
                     
                 
                    $lv_isRejectedByManager = true;
                     break;
                 }
                
         }
       
      }   
        $re_isRejectedByManager = $lv_isRejectedByManager;
        return $re_isRejectedByManager;
    }
    
    
    private function getHlocked()
    {
        $lt_data = [];
       // $lv_query = "SELECT emp_id FROM trans_locks where status = 'S121'";
        $lv_query = "SELECT emp_id FROM trans_locks where status = 'S201'";
    
        
        $lt_data = cl_DB::getResultsFromQuery($lv_query);
//        $this->deployable_emp_count = cl_DB::getCountAndReset();
//        echo $this->deployable_emp_count;
//        if($this->deployable_emp_count > 0)
//        {
//        print_r($lt_data);
            
//           $this->arr_slocked = $lt_data;
             self::$arr_hlocked = $lt_data;
             
            
            
            
//        }
            
    }
    
         public function isHardLocked($fp_v_emp_id) {
         
         $lv_hlocked = false;
//         echo $fp_v_emp_id."<br>";
         
         foreach (self::$arr_hlocked as $key => $value) {
             
         
             if(in_array($fp_v_emp_id,$value))
         {
             $lv_hlocked = true;
             break;
         }
       
      }   

        
        $re_hlocked = $lv_hlocked;
        return $re_hlocked;
    }
    
   private function getProposalRejectedByOps()
    {
        $lt_data = [];
        $lv_query = "SELECT * FROM trans_Proposals where rejected = 'X'";
        $lt_data = cl_DB::getResultsFromQuery($lv_query);
//        $this->deployable_emp_count = cl_DB::getCountAndReset();
//        echo $this->deployable_emp_count;
//        if($this->deployable_emp_count > 0)
//        {
            //print_r ($lt_data);
            self::$arr_proposalRejectedByOps = $lt_data;
//        }
    
    
    } 
      public function isRejectedByOps($fp_v_emp_id,$fp_v_so_no) {
         
         $lv_isRejectedByOps = false;
//        
//         echo "entered empID = ". $fp_v_emp_id;
//         echo "Entered  so_no   =  ". $fp_v_so_no;
         
         foreach (self::$arr_proposalRejectedByOps as $key => $value) {
             

         
         
             if(in_array($fp_v_emp_id,$value)== true)
         {
                 
                 if ($value['so_id'] == $fp_v_so_no){
                     
                 
                    $lv_isRejectedByOps = true;
                     break;
                 }
                
         }
       
      }   
        $re_isRejectedByOps = $lv_isRejectedByOps;
        return $re_isRejectedByOps;
    }
    
    
}