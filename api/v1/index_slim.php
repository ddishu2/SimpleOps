<?php

class cl_Routes
{
    const GC_ROUTE_OPEN_SO          = '/open_so(/)'; 
    const GC_ROUTE_DEPLOYABLE_EMPS  = '/deployable_emp(/)'; 
    const GC_ROUTE_EMP_FOR_SO       = '/emps_for_open_so(/)'; 
}

//Include Slim Framework Library Code
class cl_RMGTool_Globals
{
    const GC_APP_NAME               = 'RMGTool_REST_API';
    const GC_ROUTE_OPEN_SO          = '/open_so(/)'; 
    const GC_ROUTE_DEPLOYABLE_EMPS  = '/deployable_emp(/)'; 
    const GC_ROUTE_EMP_FOR_SO       = '/emps_for_open_so(/)'; 
    const OPEN_SO_DATE_RANGE    = 21;

}


//Include Slim.php library files
require __DIR__ .
        DIRECTORY_SEPARATOR .
        'libraries' .
        DIRECTORY_SEPARATOR .
        'Slim' .
        DIRECTORY_SEPARATOR .
        'Slim.php';
require __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
require __DIR__.DIRECTORY_SEPARATOR.'cl_vo_deployableEmp.php';
require __DIR__.DIRECTORY_SEPARATOR.'cl_vo_open_sos.php';

 \Slim\Slim::registerAutoloader();
 
// Instantiate a Slim Application
 $app = new \Slim\Slim();
// Set Name of App to identify the app while acquiring references
 $app ->setName(cl_RMGTool_Globals::GC_APP_NAME);
 
// Define a HTTP GET Route
 
// GET route for Open SOs
 $app->get
        (
            cl_RMGTool_Globals ::GC_ROUTE_OPEN_SO, 
            function () use ($app)
            {
                $re_it_emps_for_sos = [];
                $lo_open_sos = new cl_vo_open_sos();
                $lv_so_from_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
                $lv_so_to_date   = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);            
                $lt_open_sos = $lo_open_sos->get($lv_so_from_date, $lv_so_to_date);
                echo json_encode($lt_open_sos, JSON_PRETTY_PRINT);
            }
        );
        

//  GET route for Auto Emp. Proposals                     
 $app->get('/auto_propose(/)', 
               function () use($app) 
               {
     
     //Other logic
               }
    );

//Run the Slim application:
$app->run();
?>

