<?php
//This class represents Open SOs
class cl_vo_open_sos
{
    const C_DATE_FORMAT   = 'Y-m-d';
    const C_VIEW_OPEN_SO  = 'v_open_so';
    const C_FNAME_SO_FROM = 'so_from_date';
    const C_FNAME_SO_TO   = 'so_to_date';
    const C_DATE_COMPONENTS = 3;
    private static $count = 0;
    private static $it_open_sos = [];
    
    function __construct()
    {
    }
    
    
    private function isDateValid(&$fp_v_date)
    {
        
        $re_valid = false;
        $lv_valid = false;
        $lv_day = null; $lv_month = null; $lv_year = null;
        list($lv_year,$lv_month,$lv_day) = explode(cl_DB::C_DATE_SEPARATOR, $fp_v_date,self::C_DATE_COMPONENTS );
        if(is_numeric($lv_day)&&is_numeric($lv_month)&&is_numeric($lv_year))
        {
            $lv_valid = checkdate($lv_month, $lv_day,$lv_year );
        }
        $re_valid = $lv_valid;
        return $re_valid;
    }
    
    private function validateAndConvertDateRange(&$fp_ch_v_sdate, &$fp_ch_v_endate)
    {
//            $lv_today_date         = date(cl_DB::C_DATE_FORMAT);
//            $lv_past_date_range    = DateInterval::createFromDateString('8 weeks');
//            $lv_future_date_range  = DateInterval::createFromDateString('4 weeks');
//            if(!isDateValid($fp_ch_v_sdate))
//            {
//                $lv_sdate = date($fp_ch_v_sdate, cl_DB::C_DATE_FORMAT);
//                $lv_sdate =  date_diff($lv_sdate,$lv_past_date_range);
//                $lv_sdate = date
//            }
//            $fp_so_start_date = date(cl_DB::C_DATE_FORMAT);
//            $lv_so_end_date   = $fp_so_start_date;
//            
//            date_add($lv_so_end_date,$lv_date_range);
//            $fp_so_end_date = date($lv_so_end_date,cl_DB::C_DATE_FORMAT);
    }
//        static public function get(
//                               $fp_so_start_date = NULL,
//                               $fp_so_end_date   = NULL  ) 
//            
//    {
//        $lv_query = "SELECT * FROM `v_open_so` \n"
//                . "WHERE\n"
//                . "(\n"
//                . "	new_sdate <> '0000-00-00'\n"
//                . "AND\n"
//                . "	new_sdate BETWEEN CAST('$fp_so_start_date' AS DATE) AND CAST('$fp_so_end_date' AS DATE) \n"
//                . ")\n"
//                . "OR\n"
//                . "(\n"
//                . "	new_sdate = '0000-00-00'\n"
//                . "AND\n"
//                . "	so_sdate BETWEEN CAST('$fp_so_start_date' AS DATE) AND CAST('$fp_so_end_date' AS DATE) \n"
//                . ")\n"
//                . "ORDER BY so_submi_date ASC;";
//        $re_open_sos = cl_DB::getResultsFromQuery($lv_query);
//        return $re_open_sos;
//    }
    
    public function get($fp_so_start_date = NULL,
                        $fp_so_end_date   = NULL  ) 
            
    {   
        $lv_query = "SELECT * FROM `v_open_so` \n"
                    . "WHERE\n"
//                . "(\n"
//                . "	new_sdate <> '0000-00-00' OR \n"
//                . "AND\n"
//                . "	new_sdate BETWEEN CAST('$fp_so_start_date' AS DATE) AND CAST('$fp_so_end_date' AS DATE) \n"
//                . ")\n"
//                . "OR\n"
                 . "(\n"
                 . "	new_sdate = '0000-00-00'\n"
                 . "AND\n"
                 . "	so_sdate BETWEEN CAST('$fp_so_start_date' AS DATE) AND CAST('$fp_so_end_date' AS DATE) \n"
                 . ")\n"
                 . "ORDER BY so_submi_date ASC;";
        
        
        self::$it_open_sos = cl_DB::getResultsFromQuery($lv_query);
        self::$count       = cl_DB::getCount();
        return self::$it_open_sos;
    }

    public function isOpen() 
    {
        
    }

    public function isValid()
    {
        
    }
    
//    Cancel SO
    public function cancel($fp_v_so_id)
    {
        
    }
}