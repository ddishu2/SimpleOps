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
require_once __DIR__ .
        DIRECTORY_SEPARATOR .
        'libraries' .
        DIRECTORY_SEPARATOR .
        'Slim' .
        DIRECTORY_SEPARATOR .
        'Slim.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
//require __DIR__.DIRECTORY_SEPARATOR.'cl_deployableEmp.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_vo_open_sos.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_SOEmpSkillMatcher.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_OpenSOQueryBuilder.php';
//require __DIR__.DIRECTORY_SEPARATOR.'cl_proposalGenerator.php';
 \Slim\Slim::registerAutoloader();
 
// Instantiate a Slim Application
 $app = new \Slim\Slim();
// Set Name of App to identify the app while acquiring references
 $app ->setName(cl_RMGTool_Globals::GC_APP_NAME);
 
//// Define a HTTP GET Route
 $app->
    get(
        cl_RMGTool_Globals ::GC_ROUTE_OPEN_SO, 
        function () use ($app)
        {   
            $lv_so_from_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
            $lv_so_to_date   = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);
//            echo json_encode($lv_so_from_date);
//            echo json_encode($lv_so_to_date);
            $lo_open_sos = new cl_vo_open_sos($lv_so_from_date, $lv_so_to_date);
            $lt_open_sos = $lo_open_sos->get();
            
            $app->response->setStatus(200);
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode($lt_open_sos, JSON_PRETTY_PRINT);
        });
//               
//         $app->
//            get(cl_RMGTool_Globals ::GC_ROUTE_DEPLOYABLE_EMPS, 
//               function () use ($app)
//               {
//                    $app->response->setStatus(200);
//                    $app->response->headers->set('Content-Type', 'application/json');
//                    
//                    
////                    $re_it_emps_for_sos = [];
////                    $lo_open_sos = new cl_vo_open_sos();
//                    $lv_so_from_date = $app->request->get(cl_vo_open_sos::C_FNAME_SO_FROM);
//                    $lv_so_to_date   = $app->request->get(cl_vo_open_sos::C_FNAME_SO_TO);
//                    echo $lv_so_from_date;
//                            
////                    $lt_open_sos = $lo_open_sos->get($lv_so_from_date, $lv_so_to_date);
//////                    
////                    
////                    $c_pg = new cl_ProposalGenerator();
////                    $re_it_emps_for_sos = $c_pg->getAutoProposals($lt_open_sos);
////                    $app->response->setStatus(200);
////                    $app->response->headers->set('Content-Type', 'application/json');
////                    echo json_encode($re_it_emps_for_sos, JSON_PRETTY_PRINT);
//                   // echo json_encode($lt_open_sos, JSON_PRETTY_PRINT);
//            
//               }
//
//        );
//        
        
        
 $app->get('/so_emp_skill(/)', 
               function () use($app) 
               {
                    $lv_so_skill     = $app->request->get('so_skill' );
                    $lv_emp_skill    = $app->request->get('emp_skill');
                    echo 'SO Skill->'.$lv_so_skill.PHP_EOL.' Emp Skill->'.$lv_emp_skill.PHP_EOL;
                    $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');
                    
                    $lo_skillMatcher = new cl_SOEmpSkillMatcher();
                    $lv_matches      = $lo_skillMatcher->isMatchOrAlternative($lv_so_skill, $lv_emp_skill);
                    $lv_alt          = $lo_skillMatcher->isAlternative($lv_so_skill, $lv_emp_skill);
                    $lv_perf         = $lo_skillMatcher->isPerfectMatch($lv_so_skill, $lv_emp_skill);
                    echo 'Perfect Match';var_dump($lv_perf);
                    echo 'Alt';       var_dump($lv_alt);
                    echo 'MatchOrAlt';var_dump($lv_matches);
              }
    );

    
  $app->get('/sofilter(/)', 
               function () use($app) 
               {
                    $lv_sdate  =  $app->request->get('so_from_date');
                    $lv_endate =  $app->request->get('so_to_date');
                    $larr_so_locs    = $app->request->get('so_loc');
//                    $lv_so_region = $app->request->get('so_reg');
                    $lv_so_projid = $app->request->get('so_projid');
                    $lv_so_proj_bu = $app->request->get('so_projbu');
                    $lv_so_projname = $app->request->get('so_projname');
                    
                    $app->response->setStatus(200);
//                    $app->response->headers->set('Content-Type', 'application/json');
                    
                    $lo_openSO = new cl_vo_open_sos($lv_sdate, $lv_endate);
                    $lo_openSO->filterByContainsProjectName($lv_so_projname);
                    $lo_openSO->filterByEqualsProjBU($lv_so_proj_bu);
                    $lo_openSO->filterByInLocationList($larr_so_locs);
                    $larr_open_so = $lo_openSO->get();
                    $app->response->setStatus(200);
                    $app->response->headers->set('Content-Type', 'application/json');
                    echo json_encode($larr_open_so, JSON_PRETTY_PRINT);
                    
//                  Direct open sos nahin dekh rahe na
//                  Proposals mein include karke dekh
//                  Yeh names match hone chahiye. Siddhesj se dekh sakte hain woh
//                      SImialr code proposals mein aayega. Daala tune?
                    
//                    echo $lo_queryBuild->getQuery();
              }
    );
//Run the Slim application:
$app->run();
?>

