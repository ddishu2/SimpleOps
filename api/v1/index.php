<?php
/**
*The controller for RMGF tool. Is responnsible for routing to appropriate models 
*/

//Include Slim Framework Library Code
class cl_RMGTool_Globals
{
    const GC_APP_NAME               = 'RMGTool_REST_API';
    const GC_ROUTE_OPEN_SO          = '/open_so(/)'; 
    const GC_ROUTE_DEPLOYABLE_EMPS  = '/deployable_emp(/)'; 
    const GC_ROUTE_EMP_FOR_SO       = '/emps_for_open_so(/)'; 
    const OPEN_SO_DATE_RANGE    = 21;
//    static public $GC_SLIM_PATH = __DIR__.
//                                DIRECTORY_SEPARATOR.
//                                'libraries'.
//                                DIRECTORY_SEPARATOR.
//                                'Slim'.
//                                DIRECTORY_SEPARATOR.
////                                'Slim.php';
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
require __DIR__.DIRECTORY_SEPARATOR.'cl_deployableBUEmps.php';
require __DIR__.DIRECTORY_SEPARATOR.'cl_vo_open_sos.php';
require __DIR__.DIRECTORY_SEPARATOR.'cl_proposals.php';
 \Slim\Slim::registerAutoloader();
 
// Instantiate a Slim Application
 $app = new \Slim\Slim();
// Set Name of App to identify the app while acquiring references
 $app ->setName(cl_RMGTool_Globals::GC_APP_NAME);
 
// Define a HTTP GET Route
 $app->
    get(
        cl_RMGTool_Globals ::GC_ROUTE_OPEN_SO, 
        function () use ($app)
        {   
            $lv_so_from_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
            $lv_so_to_date   = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);
            $lo_open_sos = new cl_vo_open_sos($lv_so_from_date, $lv_so_to_date);
            $lt_open_sos = $lo_open_sos->get();
            
            $app->response->setStatus(200);
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode($lt_open_sos, JSON_PRETTY_PRINT);
        });
               
         $app->
            get(cl_RMGTool_Globals ::GC_ROUTE_DEPLOYABLE_EMPS, 
               function () use ($app)
               {
                    $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');
                    
                    
                    $re_it_emps_for_sos = [];
                   
                    $lv_so_from_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
                    $lv_so_to_date   = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);
                     
                    
                    $lo_open_sos = new cl_vo_open_sos($lv_so_from_date, $lv_so_to_date);        
//                    $lt_open_sos = $lo_open_sos->get($lv_so_from_date, $lv_so_to_date);
                  $lo_deployable_emp = new cl_deployableBUEmps();  
                    
                    $c_pg = new cl_Proposals($lo_open_sos,$lo_deployable_emp);
                    
                    $re_it_emps_for_sos = $c_pg->getAutoProposals();
                    $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');
                    echo json_encode($re_it_emps_for_sos, JSON_PRETTY_PRINT);
                   // echo json_encode($lt_open_sos, JSON_PRETTY_PRINT);
            
               }

        );
        
        
        
 $app->get('/test(/)', 
               function () use($app) 
               {
     
     $lo_deployableBUemps = new cl_deployableBUEmps();
     //$emp_id_slocked = 15992 ;
     $emp_id_rjm = 14761;
     $so_no_rjm = 314429;
     $emp_id_hlock = 62380;
     $emp_id_rjo = 39656;
     $so_no_rjo= 317437;
            // $result =  $lo_deployableBUemps->isSoftLocked($emp_id_slocked);
            //$result =  $lo_deployableBUemps->isRejectedByOps($emp_id_rjm,$so_no_rjm);
            //$result = $lo_deployableBUemps->isHardLocked($emp_id_hlock);
              $result = $lo_deployableBUemps->isRejectedByOps($emp_id_rjo, $so_no_rjo);
     
             $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');
                    echo json_encode($result, JSON_PRETTY_PRINT);
               }
    );

//Run the Slim application:
$app->run();
?>

