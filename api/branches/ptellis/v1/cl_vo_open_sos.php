<?php
//This class represents Open SOs for a given date range
class cl_vo_open_sos
{
    const C_DATE_FORMAT   = 'Y-m-d';
    const C_VIEW_OPEN_SO  = 'v_open_so';
    const C_FNAME_SO_FROM = 'so_from_date';
    const C_FNAME_SO_TO   = 'so_to_date';
    const C_SO_ID_DB_FNAME = 'so_no';
    const C_DATE_COMPONENTS = 3;
    private $v_so_sdate;
    private $v_so_endate;
    private $arr_open_sos = []; 
    
    function __construct($fp_v_so_sdate , $fp_v_so_endate)
    {
        $this->v_so_sdate   = $fp_v_so_sdate;
        $this->v_so_endate = $fp_v_so_endate;
        $this->setOpenSOs();  
    }
    
    public function get( ) 
    {
        return $this->arr_open_sos;
    }
    
    
    private function setOpenSOs()
    {
        $larr_open_sos = [];
        $larr_sos =  $this->fetchSOsForDateRange();
//        echo json_encode($larr_sos);
        foreach ($larr_sos as $lwa_so) 
        {
            $lv_so_id = $lwa_so[self::C_SO_ID_DB_FNAME];
            $lv_is_so_open = $this->isOpen($lv_so_id);
            if($lv_is_so_open == true)
            {
                $larr_open_sos[] = $lwa_so;
            }
        }
        $this->arr_open_sos =  $larr_open_sos;
    }
    
    private function fetchSOsForDateRange()
    {
        $re_sos = [];
        $lv_query = "SELECT * FROM `v_open_so` \n"
                . "WHERE\n"
                . "(\n"
                . "	new_sdate <> '0000-00-00'\n"
                . "AND\n"
                . "	new_sdate BETWEEN CAST('$this->v_so_sdate' AS DATE) AND CAST('$this->v_so_endate' AS DATE) \n"
                . ")\n"
                . "OR\n"
                . "(\n"
                . "	new_sdate = '0000-00-00'\n"
                . "AND\n"
                . "	so_sdate BETWEEN CAST('$this->v_so_sdate' AS DATE) AND CAST('$this->v_so_endate' AS DATE) \n"
                . ")\n"
                . "ORDER BY so_submi_date ASC;";
        $re_sos = cl_DB::getResultsFromQuery($lv_query);
        return $re_sos;
    }
    
    

    public function isOpen($fp_v_so_id) 
    {
          $lv_isOpen = true;
//        $v_so_rejectionCountWithinLimits = $this->isSO_RejectionCountWithinLimits($fp_v_so_id);
//        $v_so_unfulfilled                = $this->isSO_Unfulfilled($fp_v_so_id);
//        $lv_isOpen                       = $v_so_unfulfilled && $v_so_rejectionCountWithinLimits;
        return $lv_isOpen;
    }


    private function isSO_RejectionCountWithinLimits()
    {
        
    }
    
/**
* Returns true if no emps. currently soft/hard locked against so
*@return true|false
 *  */
    private function isSO_Unfulfilled()
    {
        
    }
}