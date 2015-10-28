<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Abstract class contains reusable logic to add filters(=, LIKE, IN).
 * Date: 21/09/2015
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
 abstract class cl_abs_QueryBuilder {
    const C_SQL_DATE_FORMAT        = "'%Y-%m-%d'";
    const C_SQL_STR_TO_DATE        = ' STR_TO_DATE'; 
    const C_SQL_INIT_DATE           = '0000-00-00';
    const C_SQL_MAX                 = 'MAX';
    const C_SQL_PARENTHESES_OPEN    = ' ( ';
    const C_SQL_PARENTHESES_CLOSE    = ' ) ';
    const C_SQL_SELECT           = 'SELECT'.PHP_EOL;
    const C_SQL_ALL              = '*'.PHP_EOL;
    const C_SQL_FROM             = 'FROM'.PHP_EOL;
    const C_SQL_WILDCARD_ANY     = '%';
    const C_SQL_IN           = ' IN ';
    const C_SQL_CAST         = ' CAST ( ';
    const C_SQL_AS_DATE      = ' AS DATE ) ';
    const C_SQL_EQUALS       = ' = ';
    const C_SQL_BETWEEN       = ' BETWEEN ';
    const C_SQL_QUOTE        = "'";
    const C_SQL_AND          = ' AND ';
    const C_SQL_LIKE         = ' LIKE ';
    const C_SQL_ORDER_BY     = ' ORDER BY ';
    const C_SQL_ORDER_BY_ASC = ' ASC ';
    const C_SQL_LOWER        = 'LOWER( ';
    const C_SQL_WHERE        = 'WHERE'.PHP_EOL;
    const C_DATE_DELIMITER   = '-';
    const C_COMMA_QUOTE      = "','";
    const C_COMMA            = ',';
    const C_BLANK            = '';
    const C_SPACE            = ' ';
    /**
     * YYYY-MM-DD
     */
    const C_DATE_FORMAT      = 'Y-m-d';
    const C_DATE_COUNT       = 3;
    const C_DATE_DD_INDEX    = 2;
    const C_DATE_MM_INDEX    = 1;
    const C_DATE_YY_INDEX    = 0;

    private $v_query_filters = '';
    
    final public static function selectAll($fp_v_from_table = '')
    {
        $re_query = NULL;
        if(self::isValidFilter($fp_v_from_table, 'DUMMY'))
        {
            $re_query =  self::C_SQL_SELECT
                        .self::C_SQL_ALL
                        .self::C_SQL_FROM
                        .$fp_v_from_table.PHP_EOL;
        }
        return $re_query;
        
    }
    
    
    final public static function select(array $fp_arr_columns, $fp_v_from_table = '')
    {
        $re_query = NULL;
        if(self::isArrayListValid($fp_arr_columns))
        {
            $re_colList = self::getCSVFromArray($fp_arr_columns, self::C_COMMA, '');
            $re_query =  self::C_SQL_SELECT
                    .$re_colList
                    .self::C_SQL_FROM
                    .$fp_v_from_table.PHP_EOL;
        }
        return $re_query;
        
    }
    
    final public static function  isArrayListValid(&$ch_fp_arr_list)
    {
        $re_valid = false;
        if(    (!is_null($ch_fp_arr_list))
           &&  is_array($ch_fp_arr_list) 
           &&  count($ch_fp_arr_list) > 0)
        {
            /**
            * Remove blank elements from array.
            */
            $larr_non_blank_values = array_filter($ch_fp_arr_list);
            if (count($$larr_non_blank_values) > 0)
            {
                $ch_fp_arr_list = $larr_non_blank_values;
                $re_valid = true;
            }
            
        }
        return $re_valid;
    }
    
    
    final public function max($fp_v_fname)
    {
        $re_max = '';
        if($fp_v_fname !== '' && $fp_v_fname !== null)
        {
            $re_max =  self::C_SQL_MAX
                      .self::C_SQL_PARENTHESES_OPEN
                      .$fp_v_fname
                      .self::C_SQL_PARENTHESES_CLOSE;
        }
        return $re_max;
    }
    /**
     * 
     * @return string Query with filters added.
     */
   final public function getQuery()
    {
        $lv_filters   = '';
        $lv_baseQuery = $this->getBaseQuery();
        $re_query = $lv_baseQuery;
        if(    $this->areFiltersSet())
        { 
            if(!$this->doesQueryHaveWhereClause($lv_baseQuery)
               && $this->areFiltersSet());       
            {
                $re_query = $lv_baseQuery . self::C_SQL_WHERE;
            }
            if($this->areFiltersSet())
            {
                $lv_filters = $this->v_query_filters;
            }
        }
        $lv_orderBySuffix = $this->getOrderBySuffix();
        $re_query = $lv_baseQuery     .PHP_EOL
                   .$lv_filters       .PHP_EOL
                   .$lv_orderBySuffix .PHP_EOL;
        return $re_query;
    }
        
    /**
     * Returns base query as a String.
     */
    abstract protected function getBaseQuery();
    
    /**
     * Returns Order By Suffix Clause or blank as String
     */
    abstract protected function getOrderBySuffix();
    
    /**
     * Adds an In Filter To Query.
     * 
     * @param string    fieldname 
     * @param string    field value 
     * @return boolean  Success
     */
    final public function addInFilterToQuery($fp_v_fname, $fp_arr_fvals)
    {
        
//        $re_success = false;
//        $lv_valueList = self::convertArrayToCSV($fp_arr_fvals);       
//            if(self::isValidFilter($fp_v_fname, $lv_valueList))
//            {
//                $lv_fname        = str_pad($fp_v_fname, 1, ' ', STR_PAD_BOTH);
//                $lv_inFilterList = self::addParenthesesToString($lv_valueList); 
//                $lv_filterLine   = $lv_fname.self::C_SQL_IN.$lv_inFilterList;
//                $this->addFilterLineToQuery($lv_filterLine);
//                $re_success = true;
//            }
//        return $re_success;
        $re_success = false;
        $lv_filterLine = self::getInQuery($fp_v_fname, $fp_arr_fvals);
        if($lv_filterLine != '')
        {
            $this->addFilterLineToQuery($lv_filterLine);
            $re_success = true;
        }
        return $re_success;
    }
    
    
    public static function getInQuery($fp_v_fname, $fp_arr_fvals)
    {
        $re_query = '';
        $lv_valueList = self::convertArrayToCSV($fp_arr_fvals);       
            if(self::isValidFilter($fp_v_fname, $lv_valueList))
            {
                $lv_fname        = str_pad($fp_v_fname, 1, ' ', STR_PAD_BOTH);
                $lv_inFilterList = self::addParenthesesToString($lv_valueList); 
                $lv_filterLine   = $lv_fname.self::C_SQL_IN.$lv_inFilterList;
                $re_query = $lv_filterLine;
            }
        return $re_query;
    }
    
    /**
     * Adds a Like Filter To Query.
     * 
     * @param string    fieldname 
     * @param string    field value 
     * @return boolean  Success
     */
    final public function addContainsFilterToQuery($fp_v_fname, $fp_v_fval, $lv_is_num = false)  
    {
        $re_success = false;
        if(self::isValidFilter($fp_v_fname, $fp_v_fval))
        {
            $lv_fname = self::convertToSQLLower($fp_v_fname);
            $lv_fval  = strtolower($fp_v_fval); 
            $lv_fval  = self::C_SQL_WILDCARD_ANY.
                        $lv_fval.
                        self::C_SQL_WILDCARD_ANY;
            if($lv_is_num === false)
            {
                $lv_fval = self::convertValueToSQLString($lv_fval);
            }
            $lv_filterLine = $lv_fname
                            .self::C_SQL_LIKE
                            .$lv_fval;
            $this->addFilterLineToQuery($lv_filterLine);
            $re_success = $lv_filterLine;
        }
        return $re_success;
    }
    
    /**
     * 
     * @param string $fp_v_fname
     * @param string $fp_v_fval
     * @param boolean $lv_is_num
     * @return string
     */
    final public static function getContainsQuery($fp_v_fname, $fp_v_fval, $lv_is_num = false)  
    {
        $re_query = null;
        if(self::isValidFilter($fp_v_fname, $fp_v_fval))
        {
            $lv_fname = $fp_v_fname;
            $lv_fval  = $fp_v_fval; 
            if($lv_is_num === false)
            {
                $lv_fname = self::convertToSQLLower($fp_v_fname);
                $lv_fval  = strtolower($fp_v_fval); 
                $lv_fval = self::convertValueToSQLString($lv_fval);
            }
            $lv_fval  = self::C_SQL_WILDCARD_ANY
                       .$lv_fval
                       .self::C_SQL_WILDCARD_ANY;
            $lv_filterLine = $lv_fname
                            .self::C_SQL_LIKE
                            .$lv_fval;
            $re_query = $lv_filterLine;
        }
        return $re_query;
    }
     /**
     * Adds an Equals Filter To Query.
     * 
     * @param string    fieldname 
     * @param string    field value 
     * @return boolean  Success
     */
    final public function addEqualsFilterToQuery($fp_v_fname, $fp_v_fval)
    {
        $re_success = false;
        if(self::isValidFilter($fp_v_fname, $fp_v_fval))
        {
            $lv_fname = self::convertToSQLLower($fp_v_fname);
            $lv_fval  = strtolower($fp_v_fval);
            $lv_fval = self::convertValueToSQLString($lv_fval);
            $lv_filterLine = $lv_fname
                            .self::C_SQL_EQUALS
                            .$lv_fval;
            $this->addFilterLineToQuery($lv_filterLine);
            $re_success = $lv_filterLine;
        }
        return $re_success;
    }
    
    
    final public function getEqualsClauseQuery($fp_v_fname, $fp_v_fval, $fp_v_is_num = false)
    {
        $re_query = false;
        if(self::isValidFilter($fp_v_fname, $fp_v_fval))
        {
            $lv_fname = $fp_v_fname;
            $lv_fval  = $fp_v_fval;
            if($fp_v_is_num === true)
            {
                $lv_fname = self::convertToSQLLower($fp_v_fname);
                $lv_fval  = strtolower($fp_v_fval);
                $lv_fval = self::convertValueToSQLString($lv_fval);
            }

            $lv_filterLine = $lv_fname
                            .self::C_SQL_EQUALS
                            .$lv_fval;
            $re_query = $lv_filterLine;
        }
        return $re_query;
    }
    
     /**
     * Adds a Between Filter To Query.
     * 
     * @param string     fieldname 
     * @param string    'From' field value
     * @param string    'To' field value
     * @return boolean  Success
     */
    final public function addBetweenFilterToQuery($fp_v_fname, $fp_v_fval_from, $fp_v_fval_to)
    {
        $re_success = false;
        $lv_filterLine = self::getBetweenFilterQuery($fp_v_fname, $fp_v_fval_from, $fp_v_fval_to);
        if($lv_filterLine != '')
        {
            $lv_filterLine = $fp_v_fname
                            .self::C_SQL_BETWEEN
                            .$fp_v_fval_from
                            .self::C_SQL_AND
                            .$fp_v_fval_to;
            $this->addFilterLineToQuery($lv_filterLine);
            $re_success = true;
        }
        return $re_success;
    }
    
    
    
    /**
     * Returns a SQL BETWEEN clause.
     * 
     * @param string     fieldname 
     * @param string    'From' field value
     * @param string    'To' field value
     * @return string   Non-blank if succesful, else blank
     */
    final public static function getBetweenFilterQuery($fp_v_fname, $fp_v_fval_from, $fp_v_fval_to)
    {
        $re_query = '';
        if(self::isValidFilter($fp_v_fname, $fp_v_fval_from)&& self::isValidFilter($fp_v_fname, $fp_v_fval_to))
        {
            $lv_filterLine = $fp_v_fname.        PHP_EOL
                            .self::C_SQL_BETWEEN.PHP_EOL
                            .$fp_v_fval_from.    PHP_EOL
                            .self::C_SQL_AND.    PHP_EOL
                            .$fp_v_fval_to.      PHP_EOL;
            
            $re_query = $lv_filterLine;
        }
        return $re_query;
    }
    

    
    /**
     * Converts value to an SQL string.
     *  
     * @param string    field value 
     * @return boolean  Success
     */
    final public static function convertValueToSQLString($fp_v_value)
    {
        $lv_value = $fp_v_value;
        $re_value  = self::C_SQL_QUOTE.$lv_value.self::C_SQL_QUOTE;
        return $re_value;
    }
    
     /**
     * Converts value to an SQL string.
     *  
     * @param string    field value 
     * @return boolean  Success
     */
    final public static function quote($fp_v_value)
    {
        $lv_value = $fp_v_value;
        $re_value  = self::C_SQL_QUOTE.$lv_value.self::C_SQL_QUOTE;
        return $re_value;
    }
    
    

    
    /**
     * 
     * @param array $fp_arr_values
     * @param string $fp_v_delimiter
     * @param string $fp_v_prefix_and_suffix
     * @return string CSV separated values or CSV separated quoted values
     */
    final private static function getCSVFromArray($fp_arr_values, $fp_v_delimiter = self::C_COMMA_QUOTE, $fp_v_prefix_and_suffix = self::C_SQL_QUOTE)
    {
        $re_csv = null;

        if(self::isArrayListValid($fp_arr_values))
        {
                $lv_valueList = implode($fp_v_delimiter, $fp_arr_values);
            
//            $lv_valueList = self::C_SQL_QUOTE.$lv_valueList.self::C_SQL_QUOTE; 
                $lv_valueList = $fp_v_prefix_and_suffix
                                .$lv_valueList
                                .$fp_v_prefix_and_suffix; 
                $re_csv = $lv_valueList;

        }
        return $re_csv;
    }
    
    /**
     * Converts array to a comma separated values string.
     *  
     * @param  array   $fp_arr_values
     * @param  boolean $lv_is_num True if array has number values 
     * @return string  CSV string
     */
    final public static function convertArrayToCSV(array $fp_arr_values = null, $lv_is_num = false)
    {
        $re_csv = null;
        /**
         * Default delimiter for array of strings
         */
        $lv_delimiter = self::C_COMMA_QUOTE;
        $lv_prefix_and_suffix = self::C_SQL_QUOTE;
        
        /**
         * Delimiters and prefix, suffix for Numeric values
         */
        if($lv_is_num === true)
        {
            $lv_delimiter         = self::C_COMMA;
            $lv_prefix_and_suffix = self::C_BLANK;
        }
        $re_csv = self::getCSVFromArray($fp_arr_values, $lv_delimiter, $lv_prefix_and_suffix);
//        /**
//         * Remove blank elements from array.
//         */
//        $fp_arr_values = array_filter($fp_arr_values);
//        if(!is_null($fp_arr_values)&&  is_array($fp_arr_values) && count($fp_arr_values) > 0)
//        {
//            $lv_valueList = implode($lv_delimiter, $fp_arr_values);
//            
////            $lv_valueList = self::C_SQL_QUOTE.$lv_valueList.self::C_SQL_QUOTE; 
//            $lv_valueList = $lv_prefix_and_suffix
//                           .$lv_valueList
//                           .$lv_prefix_and_suffix; 
//            $re_csv = $lv_valueList;
//        }
        return $re_csv;
    }
    

    
    /**
     * Adds given filter line to query and apppends prefixes 'WHERE' & 'AND', 
     * when necessary.
     * 
     * @param string $fp_v_filterLine 
     */
    private function addFilterLineToQuery($fp_v_filterLine)
    {
        $lv_queryLinePrefix = self::C_SQL_AND;
        $lv_filterLine = $fp_v_filterLine;
        if($this->shouldAddWhereClauseToQuery())
        {
            $lv_queryLinePrefix = self::C_SQL_WHERE;
        }
        $lv_filterLine = $lv_queryLinePrefix.$fp_v_filterLine;
        $this->v_query_filters .= $lv_filterLine.PHP_EOL;   
    }
    
    /**
     * Returns true if filters are set.
     * 
     * @return boolean True if filters are set
     */
    private function areFiltersSet()
    {
        $re_filterSet = false;
        if($this->v_query_filters !== '' || $this->v_query_filters != null)
        {
            $re_filterSet = true;
        }
        return $re_filterSet;
    }
    
    /**
     * Returns true if query has where clause.
     * 
     * @return boolean True if filters are set
     */
    private function doesQueryHaveWhereClause($fp_v_query)
    {
        $re_queryHasWhereClause = false;
        if (strpos($fp_v_query, self::C_SQL_WHERE) !== false) 
        {
            $re_queryHasWhereClause = true;
        }
        return $re_queryHasWhereClause;
    }
    
/**
 * Returns true if filter has valid scalar string or int value,
 * Returns false for blanks, null and arrays.
 * 
 * @param type $fp_v_fname Fieldname
 * @param type $fp_v_fval  Fieldvalue
 * @return boolean
 */
    final public static function isValidFilter($fp_v_fname, $fp_v_fval)
    {
        $re_valid = false;
        if($fp_v_fname != null && $fp_v_fval != null && !(is_array($fp_v_fval)))
        {
            $re_valid = true;
        }
        return $re_valid;
    }
    
     /**
     * Returns Fieldname converted to date.
     * 
     * 
     * @param string $fp_v_fname Field Name
     * @return string
     */
    final public static function getSQLDateFromString($fp_v_fname)
    {
        $lv_dummy_fvalue = "DUMMY";
        $re_cast_as_date = '';
        if(self::isValidFilter($fp_v_fname, $lv_dummy_fvalue))
        {
            $re_cast_as_date = self::C_SQL_STR_TO_DATE
                               .self::C_SQL_PARENTHESES_OPEN
                               .$fp_v_fname
                               .self::C_COMMA
                               .self::C_SQL_DATE_FORMAT
                               .self::C_SQL_PARENTHESES_CLOSE;
                                
        }
        return $re_cast_as_date;
    }
    
    /**
     * Returns fieldname wrapped in a 'LOWER' SQL clause to make
     * comparisons case-insensitive.
     * 
     * @param type $fp_v_fname Fieldname
     * @return string
     */
    final public static function convertToSQLLower($fp_v_fname)
    {
        /**
         * Add space to start and end of field name
         */
        $lv_fname = str_pad($fp_v_fname, 1, ' ', STR_PAD_BOTH);
        $re_lower = self::C_SQL_LOWER
                    .$lv_fname
                    .self::C_SQL_PARENTHESES_CLOSE;
        return $re_lower;
    }

     /**
     * Returns true if query should be preceded by 'WHERE'
     * 
     * @param type $fp_v_fname Fieldname
     * @return boolean
     */
    private function shouldAddWhereClauseToQuery()
    {
        $re_addWhere = true;
        $lv_baseQuery = $this->getBaseQuery();
        $lv_query = $lv_baseQuery. $this->v_query_filters;
        if($this->doesQueryHaveWhereClause($lv_query))
        {
            $re_addWhere = false;
        }
        return $re_addWhere;
    }
    
    /**
     * Returns string surrounded by parentheses for use in SQL 'IN' statements.
     * 
     * @param type $fp_v_string fieldname
     * @return string
     */
    public static function addParenthesesToString($fp_v_string)
    {
        $re_string = self::C_SQL_PARENTHESES_OPEN
                    .$fp_v_string
                    .self::C_SQL_PARENTHESES_CLOSE;
        return $re_string;
    }
    
    /**
     * Resets filters.
     * 
     */
    final public function resetFilters()
    {
        $this->v_query_filters = null;
    }
    
    /**
     * 
     * @param string $fp_v_date in YYYY-MM-DD format
     * @return boolean
     */
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
    
    /**
     * 
     * @param string $fp_v_start_date in YYYY-MM-DD format
     * @param string $fp_v_end_date  in YYYY-MM-DD format
     * @return boolean               true if dates are valid and 
     *                               from date <= to date.
     */
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
