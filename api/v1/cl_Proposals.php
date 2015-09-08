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
    
    function __construct(cl_vo_open_sos $fp_o_open_sos, cl_deployableBUEmps $fp_o_deployableEmp)
    {
       $this->lv_prop_id = self::setProposalID(); 
       $this->arr_open_sos      = $fp_o_open_sos->get();
       $this->lo_deployable_emp = $fp_o_deployableEmp;
       
    }
    
    public function setProposalID()
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
      
     public function createProposal( $fp_so_id , $fp_emp_id) 
    {
       $lv_query = "INSERT INTO `trans_proposals`(`prop_id`, `so_id`, `emp_id` )
       VALUE('$this->lv_prop_id', '$fp_so_id' , '$fp_emp_id')";    
       $re_create = cl_DB::postResultIntoTable($lv_query);
       return $re_create ;
    }
        
      public function genrateEmpidSoid($re_it_emps_for_sos)
       {
           foreach ( $re_it_emps_for_sos as $key => $value)
             if(array_key_exists ('emp',$value ))
             {
               $lv_empid = $value['emp'][0]['emp_id'];
               $lv_soid = $value['so']['so_no'];
               self::createProposal ($lv_empid,$lv_soid );
                          
             }
    
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
            
            
            $lv_emp = $lo_emp->getEmpForSO(
                    $lv_so_id, $lv_so_skill, $lv_so_level, $lv_so_loc);
//            echo 'Returned' . json_encode($lv_emp);
            if (!is_null($lv_emp)) 
                {
                 $re_it_emps_for_sos[$lv_so_id]['emp'] = $lv_emp;
                 }
                
          }    
          self::genrateEmpidSoid($re_it_emps_for_sos);
          return $re_it_emps_for_sos;
         
            
       
        
    
            }
            
    
    
    
    
    
/**
* Returns true if SO has been rejected MaxTimes 
*@return true|false
*/
    private static function hasProposalBeenRejectedMaxTimes($fp_v_so_id)
    {
        
        
    }
    
}
