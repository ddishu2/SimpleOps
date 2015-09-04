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
    private $arr_perfect_proposals = [];
    private $v_perfect_proposal_count = 0;
    private $arr_open_sos;
    private $arr_deployableEmp;
    
    public function __construct(cl_vo_open_sos $fp_o_open_sos,  if_deployableEmp $fp_o_deployableEmp) 
    {
        $this->setProposalID();
        $this->arr_open_sos      = $fp_o_open_sos->getFilteredData();
    }
    
    private function setProposalID()
    {
//        Write logic to retrieve max value of proposal id from table and add1 to set
//        current proposal
    }
    
    public function getAutoProposals() 
    {        
        $re_it_emps_for_sos = [];
        foreach ($this->arr_open_sos as $open_so) 
        {
            $lv_so_id                            = $open_so['so_no'];
            $lv_so_skill                         = $open_so['skill1'];
            $lv_so_loc                           = $open_so['so_loc'];
            $lv_so_level                         = $open_so['grade'];
            $re_it_emps_for_sos[$lv_so_id]['so'] = $open_so;
            $lo_emp = new cl_vo_deployableBUEmp();

        }
        if (!is_null($lv_emp)) 
        {  
            $lv_emp = $lo_emp->get(
                    $lv_so_id, $lv_so_skill, $lv_so_level, $lv_so_loc);
//            echo 'Returned' . json_encode($lv_emp);
            if (!is_null($lv_emp)) {

                $re_it_emps_for_sos[$lv_so_id]['emp'] = $lv_emp;
        }
        return $re_it_emps_for_sos;        
    }
    
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
    


}
