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
    const GC_ROUTE_APPROVE_SOFT_LOCK = '/approve_soft_lock(/)';
    const OPEN_SO_DATE_RANGE    = 21;
      const GC_route_proposals = '/proposals(/)';
    
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
<<<<<<< .mine
require __DIR__.DIRECTORY_SEPARATOR.'cl_Lock.php';

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
        
     $app->get(cl_RMGTool_Globals ::GC_ROUTE_APPROVE_SOFT_LOCK,
            function () use($app)   
        {
        $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');
                    $lv_so_id = $app->request->get(cl_Lock::C_ARR_SO_ID);
                    $lv_emp_id   = $app->request->get(cl_Lock::C_ARR_EMP_ID);
                   $so_id = [];
                   $emp_id = [];
                   $so_id[0] = 111;
                   $so_id[1] = 112;
                   $so_id[2] = 113;
                   $emp_id[0] = 221; 
                   $emp_id[1] = 222;
                   $emp_id[2] = 223;
                   $lv_obj = new cl_Lock();
                   
                   
                   
                   $lv_result = $lv_obj->ApproveSoftLock($so_id, $emp_id);
                   
                   echo $lv_result;

        
        });    
        
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

    $app->get('/test1(/)', 
               function () use($app) 
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
                    
//                    print_r($re_it_emps_for_sos);
//                    
                    foreach($re_it_emps_for_sos as $key => $value)
                    {
                        //echo $key."</br>";
                        //print_r ($value['so']);
                         $lv_arr_so = $value['so'];
                         foreach($lv_arr_so as $key1=>$value1)
                         {
                        
//                             
//                             if ($key1 == 'so_no')
//                             {
//                             $lv_so_id = $value1;
//                             echo $lv_so_id;
//                             }
                         }
//                         print_r($lv_arr_so);     
                    }                    
                    
                    
               });

//Run the Slim application:

$app->get(cl_RMGTool_Globals ::GC_route_proposals, 
               function () use($app) 
               {
      $app->response->setStatus(200);
      $app->response->headers->set('Content-Type', 'application/json');
      
      
     // $lo_open_sos = new cl_vo_open_sos($lv_so_from_date, $lv_so_to_date); 
     // $lo_deployableBUemps = new cl_deployableBUEmps();
      $lo_pg = new cl_Proposals(lo_open_sos, $lo_deployableBUemps );
      $lv_so_id = lv_so_id(5,6,7,8);      
      $lv_emp_id = lv_emp_id(7,5,6,8);
    
      
     $return_res = $c_pg->createProposal($lv_so_id , $lv_emp_id);
      
        if($return_res == true)
        {
            echo 'successfull';
        }
        else
        {
            echo 'unsuccessfull';
        }
               }
               );

 $app->get('/looptest(/)', 
               function () use($app) 
               {              
               $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');
                    
                    
                  /*  $re_it_emps_for_sos = [];
                   
                    $lv_so_from_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
                    $lv_so_to_date   = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);
                     
                    
                    $lo_open_sos = new cl_vo_open_sos($lv_so_from_date, $lv_so_to_date);        
//                    $lt_open_sos = $lo_open_sos->get($lv_so_from_date, $lv_so_to_date);
                  $lo_deployable_emp = new cl_deployableBUEmps(); 
                  $lo_cl_proposal = new cl_Proposals($lo_open_sos,$lo_deployable_emp);
                 $re_it_emps_for_sos = $lo_cl_proposal->getAutoProposals();
                 //print_r($re_it_emps_for_sos);
                    //print_r( $re_it_emps_for_sos);
                  //  foreach ( $re_it_emps_for_sos as $key => $value) {
                        
                        //echo $key . "<br>";
                        //print_r($value['so']);
                        
                        //$emp = 'emp';
                      // if(array_key_exists ('emp',$value )){
                        //print_r($value['emp']);
                        // $lv_empid = $value['emp'][0]['emp_id'];
                         // $lv_soid = $value['so']['so_no']; 
                          // call create proposal ($lv_empid ,$lv_soid)
                 */
                 $lo_cl_lock = new cl_Lock();
                 
                 $p_id = 1;
                 $emp_id = 318129;
                 $so_id = 35063;
          $lv_string = $lo_cl_lock->rejectProposal($p_id,$emp_id,$so_id);
                  echo $lv_string;
                       }
                        
                        
                    
               
               
    );
               
  $app->run();
?>

