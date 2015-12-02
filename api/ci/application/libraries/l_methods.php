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
class l_methods {
    const C_DATE_FORMAT      = 'd-m-Y';
    const C_DATE_DELIMITER   = '-';
    const C_DATE_COUNT       = 3;
    const C_DATE_DD_INDEX    = 2;
    const C_DATE_MM_INDEX    = 1;
    const C_DATE_YY_INDEX    = 0;
    
    
//        final public function addInFilterToQuery($fp_v_fname, $fp_arr_fvals)
//    {
//        
////        $re_success = false;
////        $lv_valueList = self::convertArrayToCSV($fp_arr_fvals);       
////            if(self::isValidFilter($fp_v_fname, $lv_valueList))
////            {
////                $lv_fname        = str_pad($fp_v_fname, 1, ' ', STR_PAD_BOTH);
////                $lv_inFilterList = self::addParenthesesToString($lv_valueList); 
////                $lv_filterLine   = $lv_fname.self::C_SQL_IN.$lv_inFilterList;
////                $this->addFilterLineToQuery($lv_filterLine);
////                $re_success = true;
////            }
////        return $re_success;
//        $re_success = false;
//        $lv_filterLine = self::getInQuery($fp_v_fname, $fp_arr_fvals);
//        if($lv_filterLine != '')
//        {
//            $this->addFilterLineToQuery($lv_filterLine);
//            $re_success = true;
//        }
//        return $re_success;
//    }
//    
//    
//    
//    public static function getInQuery($fp_v_fname, $fp_arr_fvals)
//    {
//        $re_query = '';
//        $lv_valueList = self::convertArrayToCSV($fp_arr_fvals);       
//            if(self::isValidFilter($fp_v_fname, $lv_valueList))
//            {
//                $lv_fname        = str_pad($fp_v_fname, 1, ' ', STR_PAD_BOTH);
//                $lv_inFilterList = self::addParenthesesToString($lv_valueList); 
//                $lv_filterLine   = $lv_fname.self::C_SQL_IN.$lv_inFilterList;
//                $re_query = $lv_filterLine;
//            }
//        return $re_query;
//    }
//    
//      private function addFilterLineToQuery($fp_v_filterLine)
//    {
//        $lv_queryLinePrefix = self::C_SQL_AND;
//        $lv_filterLine = $fp_v_filterLine;
//        if($this->shouldAddWhereClauseToQuery())
//        {
//            $lv_queryLinePrefix = self::C_SQL_WHERE;
//        }
//        $lv_filterLine = $lv_queryLinePrefix.$fp_v_filterLine;
//        $this->v_query_filters .= $lv_filterLine.PHP_EOL;   
//    }
//    
//     private function shouldAddWhereClauseToQuery()
//    {
//        $re_addWhere = true;
//        $lv_baseQuery = $this->getBaseQuery();
//        $lv_query = $lv_baseQuery. $this->v_query_filters;
//        if($this->doesQueryHaveWhereClause($lv_query))
//        {
//            $re_addWhere = false;
//        }
//        return $re_addWhere;
//    }
//    
//    
//       private function doesQueryHaveWhereClause($fp_v_query)
//    {
//        $re_queryHasWhereClause = false;
//        if (strpos($fp_v_query, self::C_SQL_WHERE) !== false) 
//        {
//            $re_queryHasWhereClause = true;
//        }
//        return $re_queryHasWhereClause;
//    }
//
//    
//   public static function addParenthesesToString($fp_v_string)
//    {
//        $re_string = self::C_SQL_PARENTHESES_OPEN
//                    .$fp_v_string
//                    .self::C_SQL_PARENTHESES_CLOSE;
//        return $re_string;
//    }
//
//
//final private static function getCSVFromArray($fp_arr_values, $fp_v_delimiter = self::C_COMMA_QUOTE, $fp_v_prefix_and_suffix = self::C_SQL_QUOTE)
//    {
//        $re_csv = null;
//
//        if(    (!is_null($fp_arr_values))
//           &&  is_array($fp_arr_values) 
//           &&  count($fp_arr_values) > 0)
//        {
//            /**
//            * Remove blank elements from array.
//            */
//            $larr_non_blank_values = array_filter($fp_arr_values);
//            if (count($larr_non_blank_values) > 0)
//            {
//                $lv_valueList = implode($fp_v_delimiter, $larr_non_blank_values);
//            
////            $lv_valueList = self::C_SQL_QUOTE.$lv_valueList.self::C_SQL_QUOTE; 
//                $lv_valueList = $fp_v_prefix_and_suffix
//                           .$lv_valueList
//                           .$fp_v_prefix_and_suffix; 
//                $re_csv = $lv_valueList;
//            }
//        }
//        return $re_csv;
//    }
//    
//    /**
//     * Converts array to a comma separated values string.
//     *  
//     * @param  array   $fp_arr_values
//     * @param  boolean $lv_is_num True if array has number values 
//     * @return string  CSV string
//     */
//    final public static function convertArrayToCSV(array $fp_arr_values = null, $lv_is_num = false)
//    {
//        $re_csv = null;
//        /**
//         * Default delimiter for array of strings
//         */
//        $lv_delimiter = self::C_COMMA_QUOTE;
//        $lv_prefix_and_suffix = self::C_SQL_QUOTE;
//        
//        /**
//         * Delimiters and prefix, suffix for Numeric values
//         */
//        if($lv_is_num === true)
//        {
//            $lv_delimiter         = self::C_COMMA;
//            $lv_prefix_and_suffix = self::C_BLANK;
//        }
//        $re_csv = self::getCSVFromArray($fp_arr_values, $lv_delimiter, $lv_prefix_and_suffix);
////        /**
////         * Remove blank elements from array.
////         */
////        $fp_arr_values = array_filter($fp_arr_values);
////        if(!is_null($fp_arr_values)&&  is_array($fp_arr_values) && count($fp_arr_values) > 0)
////        {
////            $lv_valueList = implode($lv_delimiter, $fp_arr_values);
////            
//////            $lv_valueList = self::C_SQL_QUOTE.$lv_valueList.self::C_SQL_QUOTE; 
////            $lv_valueList = $lv_prefix_and_suffix
////                           .$lv_valueList
////                           .$lv_prefix_and_suffix; 
////            $re_csv = $lv_valueList;
////        }
//        return $re_csv;
//    }

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
