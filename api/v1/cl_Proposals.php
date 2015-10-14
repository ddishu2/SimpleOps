<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_ProposalGenerator
 *
 * @author ptellis
 */


class cl_Proposals {
    private $proposal_id;
    private $arr_generated_perfect_proposals = [];
    private $lv_prop_id;
    private $arr_open_sos;
    private $arr_deployableEmp;
    private $lo_deployable_emp;
    private $lv_item_id;
    public function __construct(cl_vo_open_sos $fp_o_open_sos, cl_deployableBUEmps $fp_o_deployableEmp,$lv_so_projname,$lv_so_proj_bu,$larr_so_locs,$fp_v_proj_id,$fp_v_capability,$fp_v_cust_name) 
    {
       
        $this->lv_prop_id = self::setProposalID();
        
        $fp_o_open_sos->filterByContainsProjectName($lv_so_projname);
        $fp_o_open_sos->filterByEqualsProjBU($lv_so_proj_bu);
        $fp_o_open_sos->filterByInLocationList($larr_so_locs);
        $fp_o_open_sos->filterByContainsProjectID($fp_v_proj_id);
      
         $fp_o_open_sos->filterByEqualsCapability($fp_v_capability);
          $fp_o_open_sos->filterByContainsCustomerName($fp_v_cust_name);
        $this->arr_open_sos      = $fp_o_open_sos->get();
        $this->lo_deployable_emp = $fp_o_deployableEmp;
        $this->lv_item_id = 0;
    }
    
    private function setProposalID()
    {
        $lv_query = "select MAX(prop_id)from trans_proposals";
       $re_PID = cl_DB::getResultsFromQuery($lv_query);
        foreach($re_PID As $key=>$value)
       {
        $count = $value['MAX(prop_id)'];
       }
       $lv_pid = $count +1;
       return $lv_pid;
    }
    
    public function getAutoProposals() 
    {      $lo_emp = $this->lo_deployable_emp ;   
        $re_it_emps_for_sos = [];
        foreach ($this->arr_open_sos as $open_so) 
        {
            $lv_so_id                            = $open_so['so_no'];
            $lv_so_skill                         = $open_so['skill1'];
            $lv_so_loc                           = $open_so['so_loc'];
            $lv_so_level                         = $open_so['grade'];
            $re_it_emps_for_sos[$lv_so_id]['so'] = $open_so;
            $re_it_emps_for_sos[$lv_so_id]['prop_id'] = $this->lv_prop_id;
            
            if (!self::hasProposalBeenRejectedMaxTimes($lv_so_id)){           // find employee for SO only if 
            $lv_emp = $lo_emp->getEmpForSO(
                    $lv_so_id, $lv_so_skill, $lv_so_level, $lv_so_loc);
//            echo 'Returned' . json_encode($lv_emp);
            if (!is_null($lv_emp)) {

                $re_it_emps_for_sos[$lv_so_id]['emp'] = $lv_emp;
        }
            } 
            

        }
        
   
//         $lo_emp->setUnfilledSoAfterPerfectProp($re_it_emps_for_sos);
         self::genrateEmpidSoid($re_it_emps_for_sos); 
         
        return $re_it_emps_for_sos;        
        
    
            }
    
    
    
    
    
/**
* Returns true if SO has been rejected MaxTimes 
*@return true|false
*/public function createProposal( $fp_so_id , $fp_emp_id ) 
    {
    $this->lv_item_id ++;
     $lv_date = date("y-m-d");

//       $lv_query = "INSERT INTO `trans_proposals`(`prop_id`, `so_id`, `emp_id` )
//       VALUE('$this->lv_prop_id', '$fp_so_id' , '$fp_emp_id')";    
//       $re_create = cl_DB::postResultIntoTable($lv_query);

//    $lv_query = "INSERT INTO `trans_proposals`(`prop_id`, `so_id`, `emp_id`,`prop_item_id` )
//       VALUE('$this->lv_prop_id', '$fp_so_id' , '$fp_emp_id' , $this->lv_item_id)";    
//       $re_create = cl_DB::postResultIntoTable($lv_query);
     
     $lv_query = "INSERT INTO `trans_proposals`(`prop_id`, `so_id`, `emp_id`,`prop_item_id` ,`created_on`) 
       VALUE('$this->lv_prop_id', '$fp_so_id' , '$fp_emp_id' , $this->lv_item_id , '$lv_date')";    
       $re_create = cl_DB::postResultIntoTable($lv_query);
       return $re_create ;
    }
        
      public function genrateEmpidSoid($re_it_emps_for_sos)
       {
           foreach ( $re_it_emps_for_sos as $key => $value)
           {
             if(array_key_exists ('emp',$value))
             {
               $lv_empid = $value['emp'][0]['emp_id'];
               //echo $lv_empid."            ";
               $lv_soid = $value['so']['so_no'];
               self::createProposal ($lv_soid,$lv_empid );
              // echo "control reached here        ";
                          
             }
           }
    }
    private static function hasProposalBeenRejectedMaxTimes($lv_so_id)
    {
        $lv_result = false;
        if (cl_Lock::getRejectionCount($lv_so_id) >= 3)
        {
            $lv_result = true;
        }
        return $lv_result;
    }
    
}