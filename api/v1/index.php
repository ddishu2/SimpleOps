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
    const GC_ROUTE_APPROVE_HARD_LOCK = '/approve_hard_lock(/)';
    const GC_ROUTE_REJECT_HARD_LOCK = '/reject_hard_lock(/)';
    const GC_ROUTE_SET_HARD_LOCK = '/set_hard_lock(/)';
    const OPEN_SO_DATE_RANGE    = 21;
    const GC_route_proposals = '/proposals(/)';
    const GC_APPROVE_AMMENDMENTS = '/approve_amendments(/)';
    const GC_AMMENDMENTS = '/amendments(/)';
    const GC_GETAMMENDMENTSREPORT = '/getAmmendmentReport(/)';
    const GC_DWN_REPORT = '/download_report(/)';
    const GC_LOAD_AMMENDMENT = '/load_amendment(/)';
    const GC_CREATE_AMENDMENT_FILE = '/create_amendment_file(/)';
    const GC_SSO                   = '/sso(/)';
    const GC_23DAYS_HL_RELEASE     = '/hardlock_release_notification(/)';
    
    
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
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_deployableBUEmps.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_vo_open_sos.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_proposals.php';

require_once __DIR__.DIRECTORY_SEPARATOR.'cl_Lock.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_NotificationMails.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_getDetails.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_Ammendments.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_Reports.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_sso.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_releasenotification.php';
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
            $lv_so_from_date = $app->request->get('so_from_date');
            $lv_so_to_date   = $app->request->get('so_to_date');
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
                   
                    $lv_so_from_date = $app->request->get('so_from_date');
                    $lv_so_to_date   = $app->request->get('so_to_date');
                     $lv_so_location   = $app->request->get(cl_vo_open_sos::C_LOCATION);
                     $lv_so_project_bu   = $app->request->get(cl_vo_open_sos::C_PROJECT_BU);
                      $lv_so_project_name   = $app->request->get(cl_vo_open_sos::C_PROJECT_NAME);
                      
                      
                      
                      $lv_so_project_id  = $app->request->get(cl_vo_open_sos::C_PROJECT_ID);
                      $lv_so_cust_name  = $app->request->get(cl_vo_open_sos::C_CUST_NAME);
                       $lv_so_capability = $app->request->get(cl_vo_open_sos::C_CAPABILITY);
                      
                      
                    
                      
                    $lo_open_sos = new cl_vo_open_sos($lv_so_from_date, $lv_so_to_date);
               
//                    $lt_open_sos = $lo_open_sos->get($lv_so_from_date, $lv_so_to_date);
                  $lo_deployable_emp = new cl_deployableBUEmps();  
                    
                    $c_pg = new cl_Proposals($lo_open_sos,$lo_deployable_emp,$lv_so_project_name,$lv_so_project_bu,$lv_so_location,$lv_so_project_id,$lv_so_capability,$lv_so_cust_name);
                    
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
                    $lv_arr_so_id = $app->request->get(cl_Lock::C_ARR_SO_ID);
                    $lv_arr_emp_id   = $app->request->get(cl_Lock::C_ARR_EMP_ID);
                    $lv_arr_stat = $app->request->get(cl_lock::C_ARR_STAT);
                    $lv_prop_id = $app->request->get(cl_lock::C_PROP_ID);
//                    $lv_arr_link = $app->request->get(cl_lock::C_ARR_LINK);
//                   $so_id = [];
//                   $emp_id = [];
//                   $so_id[0] = 111;
//                   $so_id[1] = 112;
//                   $so_id[2] = 113;
//                   $emp_id[0] = 221; 
//                   $emp_id[1] = 222;
//                   $emp_id[2] = 223;
//                   $stat = [];
//                   $stat[0] = 'SoftLocked';
//                   $stat[1] = 'Rejected';
//                   $stat[2] = 'SoftLocked';
                   $lv_obj = new cl_Lock();
                   
                   
                   //$lv_prop_id = 2; 
                   
//                   $lv_result = $lv_obj->ApproveSoftLock($so_id, $emp_id,$stat,$lv_prop_id);
                    $lv_result = $lv_obj->ApproveSoftLock($lv_arr_so_id,$lv_arr_emp_id,$lv_arr_stat,$lv_prop_id);
                 
//                   echo $lv_result;
            $app->response->setStatus(200);
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode($lv_result, JSON_PRETTY_PRINT);
        
        });    
        $app->get(cl_RMGTool_Globals ::GC_ROUTE_APPROVE_HARD_LOCK, function () use($app) {
            
            $app->response->setStatus(200);
            $app->response->headers->set('Content-Type', 'application/json');
            
            $lv_trans_id = $app->request->get(cl_lock::C_TRANS_ID);
            $lv_comments = $app->request->get(cl_lock::C_COMMENTS);
             $lv_status = $app->request->get(cl_lock::C_STATUS);
            $lv_obj = new cl_Lock();
//            $lv_trans_id = 1;
            $lv_msg = "";
            if ($lv_status == 'Approve')
            {
            $lv_result = $lv_obj->ApproveHardLock($lv_trans_id,$lv_comments);//S201
                if($lv_result == 1)
                {
                $lv_msg = "resource hard locked";
                }
                else 
                 if ($lv_result == -1)
                 {
                 $lv_msg = "Error in hard locking the resource";
                 }
            }
            else 
            if($lv_status == 'Reject')
            {
            $lv_result=$lv_obj->rejectSoftLock($lv_trans_id,$lv_comments);
                if($lv_result == 1)
                {
                $lv_msg = "Resource Rejected";
                }
                else 
                 if ($lv_result == -1)
                 {
                 $lv_msg = "Error in rejecting the resource";
                 }
            }
            $app->response->setStatus(200);
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode($lv_msg, JSON_PRETTY_PRINT);
        });
//        $app->get(cl_RMGTool_Globals ::GC_ROUTE_REJECT_HARD_LOCK, function () use($app) {
//            $app->response->setStatus(200);
//            $app->response->headers->set('Content-Type', 'application/json');
//            $lv_trans_id = $app->request->get(cl_lock::C_TRANS_ID);//S221
//            $lv_comments = $app->request->get(cl_lock::C_COMMENTS);
//            $lv_obj = new cl_Lock();
////            $lv_trans_id = 1;
//            $lv_obj->rejectSoftLock($lv_trans_id,$lv_comments);
//        });
        
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
                    
                    print_r($re_it_emps_for_sos);
                    
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
               
               $app->get('/proposal_test(/)', 
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
                  $lo_cl_proposal = new cl_Proposals($lo_open_sos,$lo_deployable_emp);
                 $re_it_emps_for_sos = $lo_cl_proposal->getAutoProposals();
         
                 //   print_r( $re_it_emps_for_sos);
//                   foreach ( $re_it_emps_for_sos as $key => $value) {
                        
                     //   echo $key . "<br>";
//                        print_r($value['so']);
                        
                        //$emp = 'emp';
                      // if(array_key_exists ('emp',$value )){
                        //print_r($value['emp']);
                        // $lv_empid = $value['emp'][0]['emp_id'];
                         // $lv_soid = $value['so']['so_no']; 
                          // call create proposal ($lv_empid ,$lv_soid)
                 
//                 $lo_cl_lock = new cl_Lock();
//                 
//                 $p_id = 1;
//                 $emp_id = 318129;
//                 $so_id = 35063;
//          $lv_string = $lo_cl_lock->rejectProposal($p_id,$emp_id,$so_id);
//                  echo $lv_string;
                       }
                        
//               }  
                    
               
               
    );

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

 $app->get('/Rejectproposal_test(/)', 
               function () use($app) 
               {              
               $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');
                    
                    
//                 $re_it_emps_for_sos = [];
//                   
//                    $lv_so_from_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
//                    $lv_so_to_date   = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);
//                     
//                    
//                    $lo_open_sos = new cl_vo_open_sos($lv_so_from_date, $lv_so_to_date);        
////                    $lt_open_sos = $lo_open_sos->get($lv_so_from_date, $lv_so_to_date);
//                  $lo_deployable_emp = new cl_deployableBUEmps(); 
//                  $lo_cl_proposal = new cl_Proposals($lo_open_sos,$lo_deployable_emp);
//                 $re_it_emps_for_sos = $lo_cl_proposal->getAutoProposals();
//                 //print_r($re_it_emps_for_sos);
//                    print_r( $re_it_emps_for_sos);
//                    foreach ( $re_it_emps_for_sos as $key => $value) {
//                        
//                        echo $key . "<br>";
                        //print_r($value['so']);
                        
                        //$emp = 'emp';
                      // if(array_key_exists ('emp',$value )){
                        //print_r($value['emp']);
                        // $lv_empid = $value['emp'][0]['emp_id'];
                         // $lv_soid = $value['so']['so_no']; 
                          // call create proposal ($lv_empid ,$lv_soid)
                 
                
                    
                    
                    
                    $lo_cl_lock = new cl_Lock();
                 
                 $p_id = 1;
                 $emp_id = 318129;
                 $so_id = 35063;
          $lv_string = $lo_cl_lock->rejectProposal($p_id,$emp_id,$so_id);
                  echo $lv_string;
                       }
                        
                        
               
               
               
    );
         
    
    $app->get('/hardlock_test(/)', 
               function () use($app) 
               {
                $lv_trans_id = $app->request->get(cl_Lock::C_TRANS_ID);
                $lv_trans_id1 = 1;
                $lv_trans_id2 = 2;
               $lo_cl_Lock = new cl_Lock();
               $lo_cl_Lock->rejectSoftLock($lv_trans_id1);
               $lo_cl_Lock->setHardLock($lv_trans_id2);
               
               }
     );
  
//  $app->get(cl_RMGTool_Globals ::GC_ROUTE_SET_HARD_LOCK, 
//               function () use($app) 
//               {
//                $lv_trans_id = $app->request->get(cl_Lock::C_TRANS_ID);
//                
//               $lo_cl_Lock = new cl_Lock();
//               
//               $lo_cl_Lock->setHardLock($lv_trans_id);
//               
//               }
//     );
//     $app->get(cl_RMGTool_Globals ::GC_ROUTE_REJECT_HARD_LOCK, 
//               function () use($app) 
//               {
//                $lv_trans_id = $app->request->get(cl_Lock::C_TRANS_ID);
//                
//               $lo_cl_Lock = new cl_Lock();
//               
//                $lo_cl_Lock->rejectSoftLock($lv_trans_id);
//               
//               }
//     );
     $app->get('/getdetails_test(/)', 
               function () use($app) 
               {
         $fp_v_emp_id = 232;
         $lt_empdetails = getDetails::getEmpDetails($fp_v_emp_id);
       // print_r($lt_empdetails);
                
         $fp_v_so_no = 310596;
           $lt_sodetails = getDetails::getSODetails($fp_v_so_no);
           //print_r($lt_sodetails);
           
           $lv_link = cl_Lock::getLink($fp_v_so_no, $fp_v_emp_id,1);
           echo $lv_link;
           
           
           
               }
     );
      
       $app->get('/setcount_test(/)', 
               function () use($app) 
               {
                  
                  $cl_lock = new cl_Lock ();
                 //$lv_res =  $cl_lock->setRejectionCount(1);
                 //echo $lv_res; 
                 $lv_count = $cl_lock->getRejectionCount(3);
                   echo $lv_count;
                  
           
           
               }
                  );
                  
       $app->get(cl_RMGTool_Globals ::GC_AMMENDMENTS,
            function () use($app)   
        {
//                $sql = "SELECT * FROM `m_ammendment` ";
//               $re_ammendments = cl_DB::getResultsFromQuery($sql);
                   // $lo_ammendments = new cl_ammendments();       
                    $lv_cust_name = $app->request->get(cl_ammendments::C_CUST_NAME);
                    $lv_proj_name = $app->request->get(cl_ammendments::C_PROJ_NAME);
                    
                    $lv_arr_competency = $app->request->get(cl_ammendments::C_COMPETENCY);
           
                    $re_ammendments = cl_ammendments::getAmmendments($lv_cust_name,$lv_proj_name,$lv_arr_competency);
                    $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');           
                    echo json_encode($re_ammendments, JSON_PRETTY_PRINT);
                  
           }
                  );       
                  
         $app->get(cl_RMGTool_Globals ::GC_APPROVE_AMMENDMENTS,
            function () use($app)   
        {         
                  $lv_arr_comments = $app->request->get(cl_ammendments::C_COMMENTS);
                  $lv_arr_emp_id = $app->request->get(cl_ammendments::C_EMP_ID);
                  $lv_arr_stat = $app->request->get(cl_ammendments::C_STAT);
             //$lv_arr_result = $app->request->get(cl_ammendments::C_AMMEND_TABLE);
             
             
//                 $lv_arr_result = [];
//                $lv_arr_result[0]['id'] = '14';
//                $lv_arr_result[0]['name'] = 'Tom';
//                $lv_arr_result[0]['level'] = 'P1';
//                $lv_arr_result[0]['IDP'] = 'SAP APPS ONE';
//                $lv_arr_result[0]['loc'] = 'Mumbai';
//                $lv_arr_result[0]['bill_stat'] = 'NBT';
//                $lv_arr_result[0]['competency'] = 'ABAP';
//                $lv_arr_result[0]['curr_proj_name'] = 'ABC';
//                $lv_arr_result[0]['curr_sdate'] = '31-Dec-16';
//                $lv_arr_result[0]['curr_edate'] = '31-Dec-16';
//                $lv_arr_result[0]['proj_edate_projected'] = '31-Dec-16';
//                $lv_arr_result[0]['supervisor'] = 'Someone';
//                $lv_arr_result[0]['cust_name'] = 'someone';
//                $lv_arr_result[0]['domain_id'] = 'someone';
//                $lv_arr_result[0]['new_edate'] = '31-Jan-16';
//                $lv_arr_result[0]['new_sup_corp_id'] = 'CASE';
//                $lv_arr_result[0]['new_sup_id'] = 100;
//                $lv_arr_result[0]['new_sup_name'] = 'SomeONe new';
//                $lv_arr_result[0]['reason'] = 'some reason';
//                $lv_arr_result[0]['req_by'] = 'some one ';
//                $lv_arr_result[0]['status'] = 'Approve';
//                $lv_arr_result[0]['ops_comments'] = 'someComments';
//                
//                 
//                 $lv_arr_result[1]['id'] = '19';
//                $lv_arr_result[1]['name'] = 'Ram';
//                $lv_arr_result[1]['level'] = 'P1';
//                $lv_arr_result[1]['IDP'] = 'SAP APPS ONE';
//                $lv_arr_result[1]['loc'] = 'Mumbai';
//                $lv_arr_result[1]['bill_stat'] = 'NBT';
//                $lv_arr_result[1]['competency'] = 'ABAP';
//                $lv_arr_result[1]['curr_proj_name'] = 'ABC';
//                $lv_arr_result[1]['curr_sdate'] = '31-Dec-16';
//                $lv_arr_result[1]['curr_edate'] = '31-Dec-16';
//                $lv_arr_result[1]['proj_edate_projected'] = '31-Dec-16';
//                $lv_arr_result[1]['supervisor'] = 'Someone';
//                $lv_arr_result[1]['cust_name'] = 'someone';
//                $lv_arr_result[1]['domain_id'] = 'someone';
//                $lv_arr_result[1]['new_edate'] = '';
//                $lv_arr_result[1]['new_sup_corp_id'] = 'someone new';
//                $lv_arr_result[1]['new_sup_id'] = 100;
//                $lv_arr_result[1]['new_sup_name'] = 'SomeONe new';
//                $lv_arr_result[1]['reason'] = 'some reason';
//                $lv_arr_result[1]['req_by'] = 'some one ';
//                $lv_arr_result[1]['status'] = 'Reject';
//                $lv_arr_result[1]['ops_comments'] = 'someComments';
              
                  
               
                  
                  
//                  
//                  echo 'GET'.PHP_EOL; var_dump($_GET);
//                $data = $_GET[cl_ammendments::C_AMMEND_TABLE];
//                $datarow = json_decode($data);
//                echo json_encode($datarow);
//                echo 'amend'.PHP_EOL; var_dump($datarow);
//                echo 'POST'; var_dump ($_POST);
                
//               $lv_arr_result = $app->request->post(cl_ammendments::C_AMMEND_TABLE);
//             $lv_arr_result = $app->request->get(cl_ammendments::C_AMMEND_TABLE);
               
//                 
               $lo_ammendments = new cl_ammendments();
                  $re_result = $lo_ammendments->ApproveAmmendments($lv_arr_emp_id, $lv_arr_comments,$lv_arr_stat);
//                 $re_result = $lo_ammendments->ApproveAmmendments($lv_arr_result);
////                  $re_result = $lo_ammendments->popExistingAmendments();
////                  print_r($re_result);  
                    $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');           
                    echo json_encode($re_result, JSON_PRETTY_PRINT);
//                   echo json_encode($lv_arr_result , JSON_PRETTY_PRINT);
        }
                  );   
                  
                  
          $app->get(cl_RMGTool_Globals::GC_GETAMMENDMENTSREPORT, 
               function () use($app) 
               {
              $fp_v_start_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
              $fp_v_end_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);
              
              $lo_ammendments = new cl_ammendments();
               $re_result = $lo_ammendments->getAmmendmentsReport($fp_v_start_date,$fp_v_end_date);
               $app->response->setStatus(200);
               $app->response->headers->set('Content-Type', 'application/json');           
               echo json_encode($re_result, JSON_PRETTY_PRINT);
              
               }
                  );
                  
                  
               $app->get(cl_RMGTool_Globals::GC_DWN_REPORT, 
               function () use($app) 
               {    
                   $fp_v_report_type = $app->request->get(cl_Reports::C_RTYPE);
                    $fp_v_start_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
                    $fp_v_end_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);
                    
                  $lo_Report = new cl_Reports($fp_v_report_type, $fp_v_start_date, $fp_v_end_date);
                  $lo_Report->download();
                   
               }
                );
                
                
                $app->get(cl_RMGTool_Globals::GC_LOAD_AMMENDMENT, 
               function () use($app)
                 { 
                $lo_amendments = new cl_ammendments();
                $lo_amendments->loadAmendments();
                
                 }
                );
                $app->get(cl_RMGTool_Globals::GC_CREATE_AMENDMENT_FILE, 
               function () use($app)
                 { 
                  
                $lo_amendments = new cl_ammendments();
                $lo_amendments->createAmendmentsFile();
                
                 }
                );
                
                
          $app->get('/amm(/)', 
               function () use($app) 
               {        
                  $lo_ammendments = new cl_ammendments();
                 // $re_result = $lo_ammendments->get_ammendments_decision_taken();
                  $re_result = $lo_ammendments->isProcessed(1189);
                   $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');           
                    echo json_encode($re_result, JSON_PRETTY_PRINT);
                  
                  }
                  );   
           
// Added By Dikshant Mishra for SSO login.
           $app->get(cl_RMGTool_Globals::GC_SSO,
            function() use ($app)
                {
                    $lo_sso = new cl_sso();
                    $re_result = $lo_sso->get_username();
                    $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');           
                    echo json_encode($re_result, JSON_PRETTY_PRINT);
            }
            );
                  
// Added by Dikshant Mishra for Hard lock release notification
            $app->get(cl_RMGTool_Globals::GC_23DAYS_HL_RELEASE,
                                function() use ($app)
                {
                $fp_date_from = $app->request->get(cl_releasenotification::gc_date_from);
                $io_days      = new cl_releasenotification;
                $re_result    = $io_days->add_business_days($fp_date_from);
                $app->response->setStatus(200);
                $app->response->headers->set('Content-Type', 'application/json');           
                echo json_encode($re_result, JSON_PRETTY_PRINT);
                }
                );
                  
                  
                  
  $app->run();
?>

