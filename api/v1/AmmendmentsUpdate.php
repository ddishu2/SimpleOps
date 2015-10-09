<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'cl_DB.php';

$lv_obj = new cl_DB();
$lv_db = $lv_obj->getDBHandle();
define('CSV_PATH', 'C:/Xampp/htdocs/csvfile/');



//$csv_file = CSV_PATH . "demo.csv"; // Name of your CSV file
$csv_file = CSV_PATH . "RMT_Amendment_csv.csv";


$csvfile = fopen($csv_file, 'r');
$table = 'm_ammendment';

//$table = 'demo1';
$query1 = "TRUNCATE TABLE $table";
$lv_result1 = cl_DB::updateResultIntoTable($query1);




$query2 = "load data infile '$csv_file' into table $table fields terminated by ',' ignore 1 lines";


$lv_result2 = cl_DB::updateResultIntoTable($query2);
fclose($csvfile);
