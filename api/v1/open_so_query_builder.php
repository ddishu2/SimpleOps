<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OpenSOs
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */

require_once __DIR__.DIRECTORY_SEPARATOR.'cl_abs_QueryBuilder.php';
class open_so_query_builder extends cl_abs_QueryBuilder{
    const C_FNAME_SO_POS_NO   = 'so_pos_no';
    const C_FNAME_PROJ_ID     = 'so_proj_id';
    const C_FNAME_PROJ_NAME   = 'so_proj_name';
    const C_FNAME_CUST_NAME   = 'cust_name';
    /**
     * @var C_FNAME_BU - Business Unit
     */
    const C_FNAME_BU          = 'so_proj_bu';
    const C_FNAME_PROJ_TYPE   = 'so_proj_type';      
    /**
     * @var C_FNAME_CUST_NAME Customer Name
     */
  
    const C_FNAME_START_DATE       = 'so_start_date_new';
    const C_FNAME_DESCRIPTION      = 'so_desc';
    const C_FNAME_SERVICE_LINE     = 'so_svc_line';
    const C_FNAME_SKILL            = 'so_primary_skill';
    const C_FNAME_CAPABILITY       = 'so_capability';
    const C_FNAME_LOCATION         = 'so_loc';
    const C_FNAME_END_DATE         = 'so_end_date';
    const C_FNAME_CREATED_DATE     = 'so_create_date';
    const C_FNAME_SUBMITTED_DATE   = 'so_submi_date';
    const C_FNAME_ENTERED_BY       = 'so_submi_date';
    const C_FNAME_OWNER            = 'so_owner';
    const C_FNAME_REGION           = 'so_region';
    const C_FNAME_LEVEL            = 'so_level';
    
    const C_TABNAME                = 'v_fulfill_stat_open';
    const C_SPACE                  = ' ';
    const C_INTERVAL_SUFFIX        = ' days';
    
    const C_START_INTERVAL   = -56;
    const C_END_INTERVAL     =  28;

    const C_FNAME_NEW_SO_SDATE     = 'new_sdate';
    const C_FNAME_SO_SDATE         = 'so_sdate';
    const C_SO_SUBMI_DATE_FNAME    = 'so_submi_date';
    
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
            $this->v_so_sdate  = $fp_v_start_date;
            $this->v_so_endate = $fp_v_end_date;   
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
                      (self::C_FNAME_BU,$fp_v_proj_bu);
    }
    
    
    public function filterByInLocationList($fp_arr_locations)
    {
        return $this->addInFilterToQuery(self::C_FNAME_LOCATION, $fp_arr_locations); 
    }
    
    public function filterByContainsProjectID($fp_v_proj_id)
    {
        return $this->addContainsFilterToQuery
                      (self::C_FNAME_PROJ_ID,$fp_v_proj_id);
    }
    
    public function filterByContainsCustomerName($fp_v_cust_name)
    {
        return $this->addContainsFilterToQuery
                      (self::C_FNAME_CUST_NAME,$fp_v_cust_name);
    }
    
    public function filterByContainsProjectName($fp_v_proj_name)
    {
        return $this->addContainsFilterToQuery
                      (self::C_FNAME_PROJ_NAME, $fp_v_proj_name);
    }
    
    public function filterByEqualsCapability($fp_v_capability)
    {
       return $this->addEqualsFilterToQuery
                      (self::C_FNAME_CAPABILITY,$fp_v_capability);
    }
    
            
    public function getBaseQuery()
    {
        $lv_start_date_sql = cl_abs_QueryBuilder::getSQLDateFromString(self::C_FNAME_START_DATE);
        $lv_from_date      = cl_abs_QueryBuilder::convertValueToSQLString($this->v_so_sdate);
        $lv_to_date        = cl_abs_QueryBuilder::convertValueToSQLString($this->v_so_endate);
        $lv_start_date_between_clause = cl_abs_QueryBuilder::getBetweenFilterQuery($lv_start_date_sql, $lv_from_date, $lv_to_date);
        $re_query =  'SELECT'.PHP_EOL
                    .'*'.PHP_EOL
                    .'FROM'.PHP_EOL
                    .self::C_TABNAME.PHP_EOL
                    .'WHERE'.PHP_EOL
                    .$lv_start_date_between_clause;
                    
                
                    
        return $re_query;
    }
    
    protected function getOrderBySuffix()
    {
        $lv_submi_date_sql       = cl_abs_QueryBuilder::getSQLDateFromString(self::C_FNAME_SUBMITTED_DATE);
        $lv_query_orderby_suffix = self::C_SQL_ORDER_BY
                                  .$lv_submi_date_sql.PHP_EOL
                                  .self::C_SQL_ORDER_BY_ASC.PHP_EOL;
    }
    
}
