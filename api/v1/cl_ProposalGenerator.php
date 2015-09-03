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
class cl_ProposalGenerator {
    public function getAutoProposals($lt_open_sos) {
         $lo_emp = new cl_vo_deployableEmp();
        foreach ($lt_open_sos as $open_so) {
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

}
