<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class m_loadFiles 
{
    const ROOT_DIR           = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\\';
    const AMENDMENT_SRC_DIR  = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\CSV\\';
    const AMENDMENT_SRC_FILENAME = 'RMT_Amendment.csv';
    const RESUME_SRC_DIR     = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\Resumes\\'; 
    const AMENDMENT_DEST_DIR = 'D:\\\\rmt\\\\loadFiles\\\\Amendments\\\\';
    const EX_FILE            = 'Could not copy file';
    
    const AMENDMENT_EXCEL_FILENAME = 'Change in T&E Approver and release date of resources.xlsm';
    const AMENDMENTS_TABNAME = 'm_ammendment';
    private static $v_amendment_local_file = null;
    
    
    
   // const RAS_SRC_DIR = 'D:\\\\rmt\\\\loadFiles\\\\RAS\\\\'; 
    const RAS_SRC_DIR = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\CSV\RAS\\';
    const RAS_SRC_FILENAME = 'RAS.csv';
    const RAS_DEST_DIR= 'D:\\\\rmt\\\\loadFiles\\\\RAS\\\\';
    const RAS_TABNAME = 'm_emp_ras_copy';
    private static $v_RAS_local_file = null;
    
    
    //const FULLFILLSTAT_SRC_DIR = 'D:\\\\rmt\\\\loadFiles\\\\Fulfillment_stat\\\\'; 
    const FULLFILLSTAT_SRC_DIR = '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\CSV\FULFILLMENTSTAT\\';
    const FULLFILLSTAT_SRC_FILENAME = 'Fulfillment.csv';
    const FULLFILLSTAT_DEST_DIR= 'D:\\\\rmt\\\\loadFiles\\\\Fulfillment_stat\\\\';
    const FULLFILLSTAT_TABNAME = 'm_so_fulfill_stat';
    private static $v_FULLFILLSTAT_local_file = null;
    
    
    
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
//            $v_delete_excel = unlink($v_remote_excel);
             
        }
        else
        {
            throw new Exception(self::EX_FILE);
        }
        return self::$v_amendment_local_file;
    }
    private static function copyRASToLocal()
    {
        $v_date = date('Y_m_d_H_i_s');
        self::$v_RAS_local_file  = self::RAS_DEST_DIR.'RAS'.$v_date.'.csv';
        $v_remote_filename = self::RAS_SRC_DIR.self::RAS_SRC_FILENAME; 
       // $v_remote_excel = self::AMENDMENT_SRC_DIR.self::AMENDMENT_EXCEL_FILENAME;
        $v_copy_success_flag = copy($v_remote_filename, self::$v_RAS_local_file);
        if ($v_copy_success_flag === TRUE) 
        {
            echo 'Copied RAS Successfully from'.$v_remote_filename.' to: '.self::$v_RAS_local_file.PHP_EOL;
          //  $v_delete_success_flag = unlink($v_remote_filename);
//            $v_delete_excel = unlink($v_remote_excel);
             
        }
        else
        {
            throw new Exception(self::EX_FILE);
        }
        return self::$v_RAS_local_file;
    }
     private static function copySOFulfillStatToLocal()
    {
        $v_date = date('Y_m_d_H_i_s');
        self::$v_FULLFILLSTAT_local_file  = self::FULLFILLSTAT_DEST_DIR.'FULLFILLMENTSTAT'.$v_date.'.csv';
        $v_remote_filename = self::FULLFILLSTAT_SRC_DIR.self::FULLFILLSTAT_SRC_FILENAME; 
       // $v_remote_excel = self::AMENDMENT_SRC_DIR.self::AMENDMENT_EXCEL_FILENAME;
        $v_copy_success_flag = copy($v_remote_filename, self::$v_FULLFILLSTAT_local_file);
        if ($v_copy_success_flag === TRUE) 
        {
            echo 'Copied RRS Successfully from'.$v_remote_filename.' to: '.self::$v_FULLFILLSTAT_local_file.PHP_EOL;
          //  $v_delete_success_flag = unlink($v_remote_filename);
//            $v_delete_excel = unlink($v_remote_excel);
             
        }
        else
        {
            throw new Exception(self::EX_FILE);
        }
        return self::$v_FULLFILLSTAT_local_file;
    }
    private static function loadTableWithCSVFileData($fp_v_table_name = '', $fp_v_file_name = '',$fp_colname,$fp_empty_value)
    {
         $ci_ins =& get_instance();
        $re_success = false;
        if($fp_v_table_name   !== '' && $fp_v_table_name !== NULL
           && $fp_v_file_name !== '' && $fp_v_file_name  !== NULL)
        {
               
//            $query_empty_table      = 'Truncate TABLE '.$fp_v_table_name.';';
//             echo $query_empty_table;
//            $lv_result1             = cl_DB::updateResultIntoTable($query_empty_table);
           
            
            $ci_ins->db->from($fp_v_table_name); 
           $lv_result1 =     $ci_ins->db->truncate(); 
          // $lv_result1 =   $ci_ins->truncate($fp_v_table_name);
            
////            $query_load_table       = "LOAD DATA INFILE '$fp_v_file_name' "
////                                    . "INTO TABLE $fp_v_table_name "
////                                    . "FIELDS TERMINATED BY ','  "
////                                    . " OPTIONALLY ENCLOSED BY '\"' " 
////                                    . "LINES TERMINATED BY '\\r\\n'"
////                                    . "IGNORE 1 LINES ;";
//
////            echo $query_load_table ;
//
//            $lv_result2 = cl_DB::updateResultIntoTable($query_load_table);
           $lv_result2 =  $ci_ins->db->query("LOAD DATA local INFILE '$fp_v_file_name'"
                                   . "INTO TABLE $fp_v_table_name "
                                   . "FIELDS TERMINATED BY ','  "
                                   . " OPTIONALLY ENCLOSED BY '\"' " 
                                   . "LINES TERMINATED BY '\\r\\n'"
                                   . "IGNORE 1 LINES ");

            
//            $query_delete_blanks  = "delete from m_ammendment where m_ammendment.id = 0";
//            $lv_result3 = cl_DB::updateResultIntoTable($query_delete_blanks);
            
           $lv_result3 = self::deleteEmptyRecords($fp_v_table_name,$fp_colname,$fp_empty_value);
//            
            //echo $lv_result1.$lv_result2.$lv_result3;
            $re_success = $lv_result1 && $lv_result2 && $lv_result3;

        }
        return $re_success;
    }
    
    private static function deleteEmptyRecords($fp_v_table_name,$fp_colname,$fp_empty_value)
    { 
        
        $ci_ins =& get_instance();
             $ci_ins->db->where($fp_colname, $fp_empty_value);
           $lv_result =  $ci_ins->db->delete($fp_v_table_name); 
           return $lv_result;
    }
    
    
    
    
    /**
     * 
     */
    public static function loadAmendments()
    {
        $lv_amendments_file = self::copyAmendmentToLocal();
       
        if($lv_amendments_file !== null)
        {
            
            self::loadTableWithCSVFileData(self::AMENDMENTS_TABNAME,$lv_amendments_file,'id',0);
           
        }
    }
    public static function loadRAS()
    {
        $lv_Ras_File = self::copyRASToLocal();
        if($lv_Ras_File !== null)
        {
           self::loadTableWithCSVFileData(self::RAS_TABNAME,$lv_Ras_File,'emp_id',0); 
        }
    }
    public static function loadFULLFILLSTAT()
    {
        $lv_so_fulfill_stat_File = self::copySOFulfillStatToLocal();
        if($lv_so_fulfill_stat_File !== null)
        {
           self::loadTableWithCSVFileData(self::FULLFILLSTAT_TABNAME,$lv_so_fulfill_stat_File,'so_proj_id',0); 
        }
    }
    
    
}