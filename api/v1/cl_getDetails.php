<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class getDetails{
    
   public static function getSODetails($fp_v_so_no)
   {
       $sql = "SELECT * FROM `m_so_rrs` WHERE so_no = $fp_v_so_no ";
       $lt_result = cl_DB::getResultsFromQuery($sql); 
       return $lt_result;
   }
   public static function getEmpDetails($fp_v_emp_id)
   {
       $sql = "SELECT * FROM `m_emp_ras` WHERE emp_id = $fp_v_emp_id ";
       $lt_result = cl_DB::getResultsFromQuery($sql); 
       return $lt_result;
   }
    
}