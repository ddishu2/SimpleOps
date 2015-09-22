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
    const C_REGION_FNAME     = ' region ';
    const C_SECTOR_FNAME     = ' sector ';
    
    const C_SO_SUBMI_DATE_FNAME    = ' so_submi_date ';
    
    private $v_so_sdate  = '2015-09-17';
    private $v_so_endate = '2015-09-30';
    
    private $v_query_filters = [];
    
    public function __construct()
    {
        $this->createOpenSOView();   
    }
    
    private function createOpenSOView()
    {
        
    }
    
    public function filterByEqualsRegion($fp_v_region)
    {
        return $this->addContainsFilterToQuery
                      (self::C_REGION_FNAME,$fp_v_proj_id);
    }
    
    public function filterByEqualsSector($fp_v_sector)
    {
        $fp_v_sector = $this->convertValueToSQLString($fp_v_sector);
        return $this->addEqualsFilterToQuery
                      (self::C_SECTOR_FNAME,$fp_v_sector);
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
                    . 'FROM  `v_open_so`'                      .PHP_EOL
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
