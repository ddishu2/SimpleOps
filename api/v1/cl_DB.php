<?php

ini_set('max_execution_time', 300);


/**
 * Summary:  Reusable Logic for interacting with the database.
 *           
 * 
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 * Date: 16/09/2015.
 * @uses cl_DB()->getResultsFromQuery to retrieve results. 
 * 
 */

class cl_DB
{
    const DB_EXCEPTION     = "Could not connect to database";
    const QUERY_EXCEPTION  = "Data Error: Bad Query"; 
    const C_DATE_SEPARATOR = '-';
    const C_DATE_FORMAT    = 'Y-m-d';
    const C_DATE_INITIAL   = '0000-00-00';
    const C_QUERY_ERROR    = 'Error: No Records Found';
    const C_HOSTNAME       = "localhost";
//    const C_DB_NAME        = "rmg_tool";
//    const C_DB_NAME        = "rmt_new_masters";
//    const C_DB_NAME        = "rmt_avi";
    const C_DB_NAME        = "rmt_amendment_DB";
    const C_USER_NAME      = "root";
    const C_PASSWORD       = "";
    private static $dbhandle = null;
    private static $count    = 0;
    private static $connected_flag = false;

  function __construct()
  {
      self::setDBHandle();
  }
  
  function __destruct()
  {
      self::closeDBHandle();
  }
  
  public static function getCountAndReset()
  {
      $lv_count = self::$count;
      self::clearCount();
      return $lv_count;
  }
  
  private static function clearCount()
  {
      self::$count = 0;
  }
  
  private static function setCount($fp_v_count)
  {
      self::$count = $fp_v_count;
  }
/**
* 
*@throws DB_EXCEPTION: Could not
*/
  private static function setDBHandle()
  {
      if(is_null(self::$dbhandle))
      {
        try{
            self::$dbhandle = mysqli_connect(self::C_HOSTNAME, self::C_USER_NAME, self::C_PASSWORD, self::C_DB_NAME);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
        if (mysqli_connect_errno()) 
        {
            throw new Exception(self::DB_EXCEPTION);
        }
        else
        {
            self::$connected_flag =  true;
        }
     } 
  }
  
  static private function closeDBHandle()
  {
    if(!is_null(self::$dbhandle))
     {
        mysqli_close(self::$dbhandle);
     }
     self::$connected_flag = false;      
  }
  
  public function getDBHandle()
  {
      if(is_null(self::$dbhandle))
      {
         self::setDBHandle();
      }
      return self::$dbhandle;
  }    
  public function getConnectionStatus()
  {
      return self::$connected_flag;
  }  
  
  public static function getDBName() 
  {
      return self::$mysql_database;
  }
  
  public function getResultsFromQuery($fp_v_query)
  {
      self::setDBHandle();
      self::clearCount();
      $re_results = [];
      $lv_query_results = self::$dbhandle->query($fp_v_query);
//      Invalid queries will return false
      if($lv_query_results == false)
      {
          throw new Exception(self::QUERY_EXCEPTION);
      }
      else
        {
            $lv_count = $lv_query_results->num_rows;
            self::setCount($lv_count);
            while ($row = $lv_query_results->fetch_assoc()) {
                $re_results[] = $row;
            }
            /* free result set */
            $lv_query_results->free();
            return $re_results;
        }
  }
   
     public function updateResultIntoTable($fp_v_query)
     {
        self::setDBHandle();
//        self::clearCount();
//      $re_results = [];
        $re_success = false; 
        if (self::$dbhandle->query($fp_v_query) == true) {
            $re_success = true;
        }
        return $re_success;
    }

    public function postResultIntoTable($fp_v_query)
    {
      self::setDBHandle();
      self::clearCount();
      $re_success = false;

      if( self::$dbhandle->query($fp_v_query) === true)
      {
         $re_success =  true;
      }
      return $re_success;
    }
    
}