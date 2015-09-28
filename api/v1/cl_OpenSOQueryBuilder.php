<?php

/**
 * Description of cl_OpenSOQueryBuilder
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 * Date: 17/09/2015
 */

/**
 * 
 */
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_abs_QueryBuilder.php';

class cl_OpenSOQueryBuilder extends cl_abs_QueryBuilder
{
    const C_PROJID_FNAME     = ' so_proj_id ';
    const C_PROJNAME_FNAME   = ' so_proj_name ';
    const C_CUSTNAME_FNAME   = ' cust_name ';
    const C_LOCATION_FNAME   = ' so_loc ';
    const C_BU_FNAME         = ' so_proj_bu';
    const C_DB_TABLE         = 'v_rrs_open_so1';
    const C_SPACE            = ' ';
    const C_INTERVAL_SUFFIX  = ' days';
    
    const C_START_INTERVAL   = -56;
    const C_END_INTERVAL     =  28;

    
    const C_SO_SUBMI_DATE_FNAME    = ' so_submi_date ';
    
    protected $v_so_sdate  = null;
    protected $v_so_endate = null;
    
    /**
     * 
     * @param string $fp_v_start_date in the format YYYY-MM-DD
     * @param string $fp_v_end_date   in the format YYYY-MM-DD
     */
    
    public function __construct($fp_v_start_date, $fp_v_end_date)
    { 
        $re_valid = $this->isDateRangeValid($fp_v_start_date, $fp_v_end_date);
        if($re_valid === true)
        {
            $this->v_so_sdate  = $lv_startDate;
            $this->v_so_endate = $lv_endDate;   
        }
        else
        {
            $this->setDefaultDates();
        }
    }
    
    private function setDefaultDates()
    {
        $this->v_so_sdate  = $this->getDefaultStartDate();
        $this->v_so_endate = $this->getDefaultEndDate();
    }
    

    /**
     * 
     * @return string in YYYY-MM-DD format
     */
    public function getDefaultStartDate()
    {
        $re_start_date = $this->addDaysToDate(date(parent::C_DATE_FORMAT), self::C_START_INTERVAL);
        return $re_start_date;
    }
    
    /**
     * 
     * @return string in YYYY-MM-DD format
     */
    public function getDefaultEndDate()
    {  
        $re_end_date = $this->addDaysToDate(date(parent::C_DATE_FORMAT), self::C_END_INTERVAL);
        return $re_end_date;
    }
    
    /**
     * 
     * @param string $fp_str_date Must be a string in YYYY-MM-DD format
     * @param int $fp_v_days Negative values are subtracted, positive values are 
     *                       added.
     * @return string   in YYYY-MM-DD format
     */
    private function addDaysToDate($fp_str_date = '0000-00-00' , $fp_v_days = 0)
    {
        $lo_date               = date_create($fp_str_date);
        $lo_date_plus_interval = $fp_str_date;
        if($fp_v_days !== 0)
        {
            $str_interval = abs($fp_v_days).self::C_SPACE.self::C_INTERVAL_SUFFIX;
            $date_interval = date_interval_create_from_date_string($str_interval);
            
            if($fp_v_days > 0)
            {
                $lo_date_plus_interval = date_add($lo_date, $date_interval);
            }
            else
            {
                $lo_date_plus_interval = date_sub($lo_date, $date_interval);
            }
        }
        $re_date_plus_interval = date_format($lo_date_plus_interval,parent::C_DATE_FORMAT);
        return $re_date_plus_interval;
    }
    
    public function filterByEqualsProjBU($fp_v_proj_bu)
    {
        return $this->addEqualsFilterToQuery
                      (self::C_BU_FNAME,$fp_v_proj_bu);
    }
    
    
    public function filterByInLocationList($fp_arr_locations)
    {
        return $this->addInFilterToQuery(self::C_LOCATION_FNAME, $fp_arr_locations); 
    }
    
    public function filterByContainsProjectID($fp_v_proj_id)
    {
        return $this->addContainsFilterToQuery
                      (self::C_PROJID_FNAME,$fp_v_proj_id);
    }
    
    public function filterByContainsProjectName($fp_v_proj_name)
    {
        return $this->addContainsFilterToQuery
                      (self::C_PROJNAME_FNAME, $fp_v_proj_name);
    }
            
    protected function getBaseQuery()
    {
        $re_query =   'SELECT'                                 .PHP_EOL
                    . '*'                                      .PHP_EOL
                    . 'FROM '.self::C_DB_TABLE                 .PHP_EOL
                    . self::C_SQL_WHERE
                    .'('                                       .PHP_EOL
                        .'new_sdate <> '.cl_DB::C_DATE_INITIAL .PHP_EOL
                        .'AND'                                 .PHP_EOL
                        ."new_sdate BETWEEN CAST('$this->v_so_sdate' AS DATE) ".PHP_EOL
                        .     "AND CAST('$this->v_so_endate' AS DATE)".PHP_EOL
                    . ')'                 .PHP_EOL
                    . 'OR'                .PHP_EOL
                    . '('                 .PHP_EOL
                    .       'new_sdate = '.cl_DB::C_DATE_INITIAL.PHP_EOL
                    .       'AND'.PHP_EOL
                    .       "so_sdate BETWEEN CAST('$this->v_so_sdate' AS DATE) AND CAST('$this->v_so_endate' AS DATE)".PHP_EOL
                    . ')'.PHP_EOL;
        return $re_query;
    }
    
    protected function getOrderBySuffix()
    {
        $lv_query_orderby_suffix = self::C_SQL_ORDER_BY.self::C_SO_SUBMI_DATE_FNAME.self::C_SQL_ORDER_BY_ASC.PHP_EOL;
    }
    
}
