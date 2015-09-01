<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author ptellis
 */
interface if_deployableEmp {

public function getDeployable();
public function isDeployable($fp_v_emp_corp_id);

}
