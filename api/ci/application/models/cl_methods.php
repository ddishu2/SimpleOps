<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_methods
 *
 * @author vkhisty
 */
class cl_methods extends CI_models {
    const C_DATE_FORMAT      = 'Y-m-d';
    
    final public static function isDateValid($fp_v_date)
    {
        $re_valid = false;
        if($fp_v_date !== '' || $fp_v_date !== null)
        {
            $lv_dateComponents = explode(self::C_DATE_DELIMITER, $fp_v_date, self::C_DATE_COUNT);
            if(count($lv_dateComponents) === self::C_DATE_COUNT)
            {
                $lv_year  = $lv_dateComponents[self::C_DATE_YY_INDEX];
                $lv_month = $lv_dateComponents[self::C_DATE_MM_INDEX];
                $lv_day = $lv_dateComponents[self::C_DATE_DD_INDEX];
                $re_valid = checkdate($lv_month, $lv_day, $lv_year);
            }
        }
        return $re_valid;
    }
   final public static function isDateRangeValid($fp_v_start_date = '', $fp_v_end_date = '')
    {
        $re_valid = self::isDateValid($fp_v_start_date) &&  self::isDateValid($fp_v_end_date);
        if($re_valid === true)
        {
            $lv_start_date      = date_create($fp_v_start_date); 
            $lv_end_date        = date_create($fp_v_end_date);    
            $lv_date_difference = date_diff($lv_start_date, $lv_end_date);
            if($lv_date_difference->days < 0)
            {
                $re_valid = false;
            }
        }
        return $re_valid;
}

}