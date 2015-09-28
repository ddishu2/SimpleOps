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
    const C_SQL_INIT_DATE           = '0000-00-00';
    const C_SQL_PARENTHESES_OPEN    = ' ( ';
    const C_SQL_PARENTHESES_CLOSE    = ' ) ';
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
    const C_SQL_WHERE        = ' WHERE '.PHP_EOL;

    private $v_query_filters = '';
    
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
    
    final protected function addInFilterToQuery($fp_v_fname, $fp_arr_fvals)
    {
        $re_success = false;
        $lv_valueList = $this->convertArrayToCSV($fp_arr_fvals);       
            if($this->isValidFilter($fp_v_fname, $lv_valueList))
            {
                $lv_inFilterList = $this->addParenthesesToString($lv_valueList); 
                $lv_filterLine   = $fp_v_fname.self::C_SQL_IN.$lv_inFilterList;
                $this->addFilterLineToQuery($lv_filterLine);
                $re_success = true;
            }
        return $re_success;
    }
    /**
     * Adds a Like Filter To Query.
     * 
     * @param string    fieldname 
     * @param string    field value 
     * @return boolean  Success
     */
    final protected function addContainsFilterToQuery($fp_v_fname, $fp_v_fval)  
    {
        $re_success = false;
        if($this->isValidFilter($fp_v_fname, $fp_v_fval))
        {
            $lv_fname = $this->convertToSQLLower($fp_v_fname);
            $lv_fval  = strtolower($fp_v_fval); 
            $lv_fval  = self::C_SQL_WILDCARD_ANY.
                        $lv_fval.
                        self::C_SQL_WILDCARD_ANY;
            $lv_fval = $this->convertValueToSQLString($lv_fval);
            $lv_filterLine = $lv_fname
                            .self::C_SQL_LIKE
                            .$lv_fval;
            $this->addFilterLineToQuery($lv_filterLine);
            $re_success = true;
        }
        return $re_success;
    }
    
     /**
     * Adds an Equals Filter To Query.
     * 
     * @param string    fieldname 
     * @param string    field value 
     * @return boolean  Success
     */
    final protected function addEqualsFilterToQuery($fp_v_fname, $fp_v_fval)
    {
        $re_success = false;
        if($this->isValidFilter($fp_v_fname, $fp_v_fval))
        {
            $lv_fname = $this->convertToSQLLower($fp_v_fname);
            $lv_fval  = strtolower($fp_v_fval);
            $lv_filterLine = $lv_fname
                            .self::C_SQL_EQUALS
                            .$lv_fval;
            $this->addFilterLineToQuery($lv_filterLine);
            $re_success = true;
        }
        return $re_success;
    }
    
     /**
     * Adds a Between Filter To Query.
     * 
     * @param string     fieldname 
     * @param string    'From' field value
     * @param string    'To' field value
     * @return boolean  Success
     */
    final protected function addBetweenFilterToQuery($fp_v_fname, $fp_v_fval_from, $fp_v_fval_to)
    {
        $re_success = false;
        if($this->isValidFilter($fp_v_fname, $fp_v_fval_from)&& $this->isValidFilter($fp_v_fname, $fp_v_fval_to))
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
     * Converts value to an SQL string.
     *  
     * @param string    field value 
     * @return boolean  Success
     */
    final public function convertValueToSQLString($fp_v_value)
    {
        $lv_value = $fp_v_value;
        $re_value  = self::C_SQL_QUOTE.$lv_value.self::C_SQL_QUOTE;
        return $re_value;
    }
    
    /**
     * Converts array to a comma separated values string.
     *  
     * @param  array   $fp_arr_values
     * @return string  CSV string
     */
    final public function convertArrayToCSV($fp_arr_values)
    {
        $re_csv = null;
        $lc_comma_quote = "','";
        if(!is_null($fp_arr_values))
        {
            $lv_valueList = implode($lc_comma_quote, $fp_arr_values);
            $lv_valueList = self::C_SQL_QUOTE.$lv_valueList.self::C_SQL_QUOTE; 
            $re_csv = $lv_valueList;
        }
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
    
    private function isValidFilter($fp_v_fname, $fp_v_fval)
    {
        $re_valid = false;
        if($fp_v_fname != null && $fp_v_fval != null && !(is_array($fp_v_fval)))
        {
            $re_valid = true;
        }
        return $re_valid;
    }
    
     /**
     * Returns YYYY-MM-DD value as an SQL Date.
     * 
     * 
     * @param string $fp_v_date YYYY-MM-DD Date
     * @return string
     */
    private function convertToSQLDate($fp_v_date)
    {
        $lv_dummy_fname = "DUMMY";
        $re_cast_as_date = null;
        if($this->isValidFilter($lv_dummy_fname, $fp_v_date))
        {
            $re_cast_as_date = self::C_SQL_CAST
                    .$fp_v_date
                    .self::C_SQL_AS_DATE;
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
    private function convertToSQLLower($fp_v_fname)
    {
        $re_lower = self::C_SQL_LOWER
                    .$fp_v_fname
                    .self::C_SQL_PARENTHESES_CLOSE;
        return $re_lower;
    }

     /**
     * Returns true if query should be preceded by 'WHERE'
     * 
     * @param type $fp_v_fname Fieldname
     * @return string
     */
    private function shouldAddWhereClauseToQuery()
    {
        $re_addPrefix = false;
        $lv_baseQuery = $this->getBaseQuery();
        $lv_query = $lv_baseQuery. $this->v_query_filters;
        if($this->doesQueryHaveWhereClause($lv_query))
        {
            $re_addPrefix = true;
        }
        elseif($this->areFiltersSet())
        {
            $re_addPrefix = true;
        }
        return $re_addPrefix;
    }
    
    /**
     * Returns string surrounded by parentheses for use in SQL 'IN' statements.
     * 
     * @param type $fp_v_fname Fieldname
     * @return string
     */
    private function addParenthesesToString($fp_v_string)
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
}
