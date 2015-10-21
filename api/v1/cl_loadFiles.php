<?php
/**
 * Description of cl_loadFiles
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */

require_once __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
class cl_loadFiles 
{
    const ROOT_DIR           = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\\';
    const AMENDMENT_SRC_DIR  = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\Ammendment\\';
    const AMENDMENT_SRC_FILENAME = 'RMT_Amendment.csv';
    const RESUME_SRC_DIR     = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\Resumes\\'; 
    const AMENDMENT_DEST_DIR = 'D:\rmt\loadFiles\\';
    const EX_FILE            = 'Could not copy file';
    
    private static $v_amendment_local_file = null;

    private static function copyAmendmentToLocal()
    {
        $v_date = date('Y_m_d_H_i_s');
        self::$v_amendment_local_file  = self::AMENDMENT_DEST_DIR.'amendment'.$v_date.'.csv';
        $v_remote_filename = self::AMENDMENT_SRC_DIR.self::AMENDMENT_SRC_FILENAME; 
        $v_copy_success_flag = copy($v_remote_filename, self::$v_amendment_local_file);
        if ($v_copy_success_flag === TRUE) 
        {
            echo 'Copied Amendment Successfully from'.$v_remote_filename.' to: '.self::$v_amendment_local_file.PHP_EOL;
            
        }
        else
        {
            throw new Exception(self::EX_FILE);
        }
        return self::$v_amendment_local_file;
    }
    
    private static function loadTableWithCSVFileData($fp_v_table_name = '', $fp_v_file_name = '')
    {
        $re_success = false;
        if($fp_v_table_name   !== '' && $fp_v_table_name !== NULL
           && $fp_v_file_name !== '' && $fp_v_file_name  !== NULL)
        {
            $query_empty_table      = 'TRUNCATE TABLE '.$fp_v_table_name.';';
            $lv_result1             = cl_DB::updateResultIntoTable($query_empty_table);
            $query_load_table       = "LOAD DATA INFILE '$fp_v_file_name' "
                                    . "INTO TABLE $fp_v_table_name "
                                    . "FIELDS TERMINATED BY ',' "
                                    . "IGNORE 1 LINES ;";
            $lv_result2 = cl_DB::updateResultIntoTable($query_load_table);
            $re_success = $lv_result1 && $lv_result2;
        }
        return $re_success;
    }
    
    /**
     * 
     */
    public static function loadAmendments()
    {
        $lv_amendments_file = self::copyAmendmentToLocal();
        if($lv_amendments_file !== null)
        {
            self::loadTableWithCSVFileData(cl_ammendments::AMENDMENTS_TABNAME,$lv_amendments_file );
        }
    }
}
