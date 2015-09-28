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
    
    const C_START_INTERVAL   = '56 days';
    const C_END_INTERVAL     = '28 days';

    
    const C_SO_SUBMI_DATE_FNAME    = ' so_submi_date ';
    
    protected $v_so_sdate  = null;
    protected $v_so_endate = null;
    
    public function __construct($fp_v_start_date, $fp_v_end_date)
    { 
        $lv_startDate =  $fp_v_start_date;
        $lv_endDate =    $fp_v_end_date;
        $re_valid = $this->isDateRangeValid($lv_startDate, $lv_endDate );
        if($re_Valid === true)
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
    

    public function getDefaultStartDate()
    {
        $today                   = date(parent::C_DATE_FORMAT);
        $lv_interval             = date_interval_create_from_date_string(self::C_START_INTERVAL);
        $startDate               = date_diff($today, $lv_interval);
        $re_formatted_start_date = $startDate->format(parent::C_DATE_FORMAT);
        return $re_formatted_start_date;
    }
    
    public function getDefaultEndDate()
    {
        $today                 = date(parent::C_DATE_FORMAT);
        $lv_interval           = date_interval_create_from_date_string(self::C_END_INTERVAL);
        $endDate               = date_add($today, $lv_interval);
        $re_formatted_end_date = $endDate->format(parent::C_DATE_FORMAT);
        return $re_formatted_end_date;
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
