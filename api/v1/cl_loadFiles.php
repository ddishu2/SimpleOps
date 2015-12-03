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
    const AMENDMENT_DEST_DIR = 'D:\\\\rmt\\\\loadFiles\\\\';
    const EX_FILE            = 'Could not copy file';
    
    const AMENDMENT_EXCEL_FILENAME = 'Change in T&E Approver and release date of resources.xlsm';
    
    private static $v_amendment_local_file = null;

    private static function copyAmendmentToLocal()
    {
        $v_date = date('Y_m_d_H_i_s');
        self::$v_amendment_local_file  = self::AMENDMENT_DEST_DIR.'amendment'.$v_date.'.csv';
        $v_remote_filename = self::AMENDMENT_SRC_DIR.self::AMENDMENT_SRC_FILENAME; 
        $v_remote_excel = self::AMENDMENT_SRC_DIR.self::AMENDMENT_EXCEL_FILENAME;
        $v_copy_success_flag = copy($v_remote_filename, self::$v_amendment_local_file);
        if ($v_copy_success_flag === TRUE) 
        {
            echo 'Copied Amendment Successfully from'.$v_remote_filename.' to: '.self::$v_amendment_local_file.PHP_EOL;
            $v_delete_success_flag = unlink($v_remote_filename);
            $v_delete_excel = unlink($v_remote_excel);
             
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
               
            $query_empty_table      = 'Truncate TABLE '.$fp_v_table_name.';';
             echo $query_empty_table;
            $lv_result1             = cl_DB::updateResultIntoTable($query_empty_table);
           
            $query_load_table       = "LOAD DATA INFILE '$fp_v_file_name' "
                                    . "INTO TABLE $fp_v_table_name "
                                    . "FIELDS TERMINATED BY ','  "
                                    . " OPTIONALLY ENCLOSED BY '\"' " 
                                    . "LINES TERMINATED BY '\\r\\n'"
                                    . "IGNORE 1 LINES ;";

//            echo $query_load_table ;

            $lv_result2 = cl_DB::updateResultIntoTable($query_load_table);

            
            $query_delete_blanks  = "delete from m_ammendment where m_ammendment.id = 0";
            $lv_result3 = cl_DB::updateResultIntoTable($query_delete_blanks);
            //echo $lv_result1.$lv_result2.$lv_result3;
            $re_success = $lv_result1 && $lv_result2 && $lv_result3;

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
