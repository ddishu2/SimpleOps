<?php

/**
 * Description of Locks
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class Locks extends CI_Controller
{
    

    public function __construct() 
    {
       parent::__construct();
        $this->load->model('m_open_so');
        $this->load->model('m_proposals');
        $this->load->model('m_BuEmployees');
        $this->load->model('m_lock');
//        $this->load->model('m_SOEmpSkillMatcher');
    }
    
               
    public function approve_soft_lock()
    {
                    $lv_arr_so_id  = $this->input->get(m_lock::C_ARR_SO_ID);
                    $lv_arr_emp_id = $this->input->get(m_Lock::C_ARR_EMP_ID);
                    $lv_arr_stat   = $this->input->get(m_lock::C_ARR_STAT);
                    $lv_prop_id    = $this->input->get(m_lock::C_PROP_ID);
                    $lv_arr_Multi  = $this->input->get(m_lock::C_MULTI);
                    
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
//                    $lv_obj = new cl_Lock();
                   
                   
                   //$lv_prop_id = 2; 
                   
//                   $lv_result = $lv_obj->ApproveSoftLock($so_id, $emp_id,$stat,$lv_prop_id);
                    $lv_result = $this->m_lock->ApproveSoftLock($lv_arr_so_id,$lv_arr_emp_id,$lv_arr_stat,$lv_prop_id,$lv_arr_Multi);
                    
//                   echo $lv_result;
          
                    echo json_encode($lv_result, JSON_PRETTY_PRINT);
    }
    
    public function approve_hard_lock()
            
    {
            $lv_trans_id =  $this->input->get(m_lock::C_TRANS_ID);
            $lv_comments =  $this->input->get(m_lock::C_COMMENTS);
            $lv_status =  $this->input->get(m_lock::C_FNAME_STATUS);
            
            $lv_smart_project_code = $this->input->get(m_lock::C_SMART_PROJECT_CODE);
            $lv_FTE = $this->input->get(m_lock::C_FTE);
            $lv_tag_type = $this->input->get(m_lock::C_TAG_TYPE);
            $lv_sdate = $this->input->get(m_lock::C_HLOCK_SDATE);
            $lv_edate = $this->input->get(m_lock::C_HLOCK_EDATE);
            $lv_tne_id = $this->input->get(m_lock::C_HLOCK_TNE_ID);
            $lv_tne_name = $this->input->get(m_lock::C_HLOCK_TNE_NAME);
//            $lv_obj = new cl_Lock();
//            $lv_trans_id = 1;
            $lv_msg = "";
//          echo $lv_trans_id;
//          echo $lv_comments;
//          echo $lv_status;
            if ($lv_status == 'Approve')
            {
              
            $lv_result = $this->m_lock->ApproveHardLock($lv_trans_id,$lv_comments,$lv_smart_project_code,$lv_FTE,$lv_tag_type,$lv_sdate,$lv_edate,$lv_tne_id,$lv_tne_name);//S201
                if($lv_result == 1)
                {
                $lv_msg = "resource hard locked";
                }
                else 
                 if ($lv_result == -1)
                 {
                 $lv_msg = "Error in hard locking the resource";
                 }
                 if ($lv_result == -2){
                     $lv_msg = 'Please enter the date in the format YYY-MM-DD';
                     }
            }
            else 
            if($lv_status == 'Reject')
            {
           // $lv_result=$this->m_lock->rejectSoftLock($lv_trans_id,$lv_comments);
           $lv_result=$this->m_lock->rejectSoftLock($lv_trans_id,$lv_comments,$lv_smart_project_code,$lv_FTE,$lv_tag_type,$lv_sdate,$lv_edate,$lv_tne_id,$lv_tne_name);
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
            
            echo json_encode($lv_msg, JSON_PRETTY_PRINT);
    }
    public function getwhereProposed()
    {
         $lv_emp_id = $this->input->get(m_Lock::C_ARR_EMP_ID);
         
          $lv_result=$this->m_lock->getDetailsWhereEmpIsAlreadyProposed($lv_emp_id);
          $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));
    }
//    public function getslockexp()
//    {
//       $lv_from_date =  $this->input->get(m_lock::C_FROM_DATE);
//       $lv_to_date =  $this->input->get(m_lock::C_TO_DATE);
//       
////       echo $lv_from_date;
////       echo $lv_to_date;
//       
//       $lv_result=$this->m_lock->getDataSlockExpired($lv_from_date,$lv_to_date);
//          $this->output
//        ->set_content_type('application/json')
//        ->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));
//    }
//    
}
