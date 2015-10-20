<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_Ammendments.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_loadFiles.php';

$lv_amendment_loadFile = cl_loadFiles::loadAmendments();

////$table = 'demo1';
//$query_empty_amendments     = 'TRUNCATE TABLE '.$amendments_table.';'.PHP_EOL;
//$lv_result1                 = cl_DB::updateResultIntoTable($query1);
//$query_load_ammendments     = "LOAD DATA INFILE '$lv_amendment_loadFile' "
//                            . " INTO TABLE $table_name "
//                            . "FIELDS TERMINATED BY ',' "
//                            . "IGNORE 1 LINES";
//
//
//$lv_result2 = cl_DB::updateResultIntoTable($query2);


//$o_filehandle = fopen($v_remote_filename, 'r');
//$lv_obj = new cl_DB();
//$lv_db = $lv_obj->getDBHandle();
//define('CSV_PATH', 'C:/Xampp/htdocs/csvfile/');
//define('CSV_PATH', "\\ntbomfs001\Datagrp\AppsOne SAP RMT\Ammendment");


//$csv_file = CSV_PATH . "demo.csv"; // Name of your CSV file
//$csv_file = CSV_PATH . "RMT_Amendment_csv.csv";
//$csv_file = CSV_PATH . "\RMT_Amendment.csv";

//$v_remote_filename =  '\\\\10.75.250.149\Datagrp\AppsOne SAP RMT\Ammendment\RMT_Amendment.csv';
//$v_local_filename  =  'D:\XAMPP\htdocs\rmt\load_files\copyAmend.csv';
//
//$v_copy_success_flag = copy($v_remote_filename, $v_local_filename);
//if ($v_copy_success_flag === TRUE) 
//{
//    echo "Copied Successfully";
//}
//else
//{
//    echo "Failed to Copy";
//}

////$table = 'demo1';
//$query1 = 'TRUNCATE TABLE '.$table_name.';'.PHP_EOL;
//$lv_result1 = cl_DB::updateResultIntoTable($query1);
//$query2 = "LOAD DATA INFILE '$v_remote_filename' into table $table_name fields terminated by ',' ignore 1 lines";
//
//
//$lv_result2 = cl_DB::updateResultIntoTable($query2);
//fclose($o_filehandle);



