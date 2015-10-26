<?php

$lv_update_fulfill_status = 
       'UPDATE'.PHP_EOL
     . 'm_so_fulfill_stat'.PHP_EOl
     . 'SET'.PHP_EOL
    . "	`so_start_date_new` = str_to_date(`so_start_date_new`,\'%c/%e/%Y\'),".PHP_EOL
    . "	`so_end_date`       = str_to_date(`so_end_date`      ,\'%c/%e/%Y\'),".PHP_EOL
    . "	`so_create_date`    = str_to_date(`so_create_date`   ,\'%c/%e/%Y\'),".PHP_EOL
    . "	`so_submi_date`     = str_to_date(`so_submi_date`    ,\'%c/%e/%Y\') ".PHP_EOL;


?>

