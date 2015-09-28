<?php
/**
 * Summary:  Contains Logic to Process Open SOs(query).
 * 
 * 
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 * Date: 16/09/2015.
 * @uses cl_DB()->getResultsFromQuery to retrieve results. 
 * 
 */
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_OpenSOQueryBuilder.php';

class cl_vo_open_sos extends cl_OpenSOQueryBuilder
{
//    const cl_DB::C_DATE_FORMAT   = 'Y-m-d';
    const C_VIEW_OPEN_SO  = 'v_open_so';
    const C_FNAME_SO_FROM = 'so_from_date';
    const C_FNAME_SO_TO   = 'so_to_date';
    const C_SO_ID_DB_FNAME = 'so_no';
    const C_DATE_COMPONENTS = 3;
//    private $v_so_sdate;
//    private $v_so_endate;
    private $arr_open_sos = []; 
    public static $arr_lockedso = [];
    
    
    function __construct($fp_v_so_sdate , $fp_v_so_endate)
    {
        parent::__construct($fp_v_so_sdate , $fp_v_so_endate);
//        $this->v_so_sdate   = $fp_v_so_sdate;
//        $this->v_so_endate  = $fp_v_so_endate;
    }
    
    private function setDefaultStartDate()
    {
        
    }
    private function setDefaultEndDate()
    {
        
    }
    
    public function get( ) 
    {
        $this->setOpenSOs();  
        return $this->arr_open_sos;
    }
    
    
    private function setOpenSOs()
    {
         
        $larr_open_sos = [];
        $larr_sos =  $this->fetch();
//        echo json_encode($larr_sos);
        foreach ($larr_sos as $lwa_so) 
        {
            $lv_so_id = $lwa_so[self::C_SO_ID_DB_FNAME];
//            $lv_is_so_open = $this->isOpen($lv_so_id);
//            if($lv_is_so_open == true)
//            {
                $larr_open_sos[] = $lwa_so;
//            }
        }
        $this->arr_open_sos =  $larr_open_sos;
    }
    
    
    
    
    private function fetch()
    {
        $re_sos   = [];
        $lv_query = parent::getQuery();
//        echo $lv_query;
        $re_sos = cl_DB::getResultsFromQuery($lv_query);
        return $re_sos;
    }
    
    

    public function isOpen($fp_v_so_id) 
    {
//      //  print_r(self::$arr_lockedso);
//          $re_open = false;
////        $v_so_rejectionCountWithinLimits = $this->isSO_RejectionCountWithinLimits($fp_v_so_id);
////        $v_so_unfulfilled                = $this->isSO_Unfulfilled($fp_v_so_id);
////        $lv_isOpen                       = $v_so_unfulfilled && $v_so_rejectionCountWithinLimits;
//        $lv_query = 'SELECT'.PHP_EOL
//                    .'so_no'.PHP_EOL
//                    .'FROM'.PHP_EOL
//                    .'v_open_so'.PHP_EOL
//                    .'LIMIT 1';
//        $lv_so_id = cl_DB::getResultsFromQuery($lv_query);
//        $lv_count = cl_DB::getCountAndReset();
//        if($lv_count === 1)
//        {
            $re_open = true;
//        }
        return $re_open;
    }
    
    private function isSO_RejectionCountWithinLimits()
    {
//     
    }
    
/**
* Returns true if no emps. currently soft/hard locked against so
*@return true|false
 *  */
    private function isSO_Unfulfilled()
    {
        
    }
 // changes by tejas
    
  private function getLocked()
  {
      $sql = "select so_id from trans_locks where status in ('S121','S201')";
      self::$arr_lockedso = cl_DB::getResultsFromQuery($sql);
//             print_r(self::$arr_lockedso);
  }
    
   
    
    private function isSOLocked($fp_v_so_id)
    {
      // print_r(self::$arr_lockedso);
        $lv_slocked = false;
        foreach (self::$arr_lockedso as $key => $value) 
        {
            if(in_array($fp_v_so_id,$value))
            {
                 $lv_slocked = true;
                break;
            }
        }
        $re_slocked = $lv_slocked;
        return $re_slocked;
    }
}