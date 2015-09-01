<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_Lock
 *
 * @author ptellis
 */
class cl_Lock {

public function __construct(){}
public function getSoftLockedEmps(){}
public function getHardLockedEmps(){}
public function getSoftLockExpiredEmps(){}
public function setSoftLock($fp_v_emp_corp_id, $fp_v_so_id){}
public function setHardLock($fp_v_emp_corp_id, $fp_v_so_id){}
public function rejectProposal($fp_v_emp_corp_id, $fp_v_so_id){}
public function rejectSoftLock($fp_v_emp_corp_id, $fp_v_so_id){}


        
}
