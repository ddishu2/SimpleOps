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
    
    private $arr_open_sos;
    private $arr_deployableEmp;
    private $lo_deployable_emp;
    public function __construct(cl_vo_open_sos $fp_o_open_sos, cl_deployableBUEmps $fp_o_deployableEmp) 
    {
        $this->setProposalID();
        $this->arr_open_sos      = $fp_o_open_sos->get();
        $this->lo_deployable_emp = $fp_o_deployableEmp;
    }
    
    private function setProposalID()
    {
//        Write logic to retrieve max value of proposal id from table and add1 to set
//        current proposal
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
         
            
            
            $lv_emp = $lo_emp->getEmpForSO(
                    $lv_so_id, $lv_so_skill, $lv_so_level, $lv_so_loc);
//            echo 'Returned' . json_encode($lv_emp);
            if (!is_null($lv_emp)) {

                $re_it_emps_for_sos[$lv_so_id]['emp'] = $lv_emp;
        }
            
            

        }
        
        
          
            
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
