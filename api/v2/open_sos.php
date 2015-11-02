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
require_once __DIR__.DIRECTORY_SEPARATOR.'open_so_query_builder.php';
class open_sos extends open_so_query_builder
{

    private $arr_open_sos = []; 
    
    
    const C_DATE_FORMAT   = 'Y-m-d';

    
    const C_DATE_COMPONENTS = 3;
    protected $v_so_sdate;
    protected $v_so_endate;
   
    public static $arr_lockedso = [];
    
    function __construct($fp_v_so_sdate , $fp_v_so_endate)
    {
        parent::__construct($fp_v_so_sdate , $fp_v_so_endate);
    }
        
    /**
     * 
     * @return array Return Open SOs
     */
    
    public function get( ) 
    {
        $this->set();  
        return $this->arr_open_sos;
    }
    
    
    /**
     * Set open SOs.
     * 
     */
    private function set()
    {
        $larr_open_sos = [];
        $lv_query = parent::getQuery();
        $larr_sos = cl_DB::getResultsFromQuery($lv_query);
        foreach ($larr_sos as $lwa_so) 
        {
            $lv_so_id = $lwa_so[self::C_FNAME_SO_POS_NO];
            $larr_open_sos[$lv_so_id] = $lwa_so;
        }
        $this->arr_open_sos =  $larr_open_sos;
    }
    
    /**
     * 
     * @param type string
     * @return boolean
     */
    public static function isOpen($fp_v_so_id) 
    {

        $re_open = false;
        $lv_query = 'SELECT'.PHP_EOL
                    .self::C_FNAME_SO_POS_NO.PHP_EOL
                    .'FROM'.PHP_EOL
                    .self::C_TABNAME.PHP_EOL
                    .'LIMIT 1';
        $lv_so_id = cl_DB::getResultsFromQuery($lv_query);
        $lv_count = cl_DB::getCountAndReset();
        if($lv_count === 1)
        {
            $re_open = true;
        }
        return $re_open;
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
