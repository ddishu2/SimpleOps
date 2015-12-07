<?php
/**
 * Description of Reports
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class Reports extends CI_Controller
{
     const C_RTYPE = 'type';
    const C_FROM_DATE = 'so_from_date';
    const C_TO_DATE = 'so_to_date';
     Const C_PROJ_NAME = 'proj_name';
    const C_PROJ_LOC  = 'proj_loc';
    const C_PROJ_ID   = 'proj_id';
    const C_CUST_NAME = 'cust_name';
    const C_CAPABILITY = 'capability'; 
    const C_PROJ_BU = 'proj_bu';
    const C_TYPE = 'so_type';
    const C_OPEN_SO_REPORT = 'Open_So_Report';
    
    public function __construct() 
    {
        parent::__construct();
        
        $this->load->model('m_report');
        $this->load->model('m_lock');
        $this->load->model('m_amendment');
        $this->load->model('m_open_so');
    }
    
    public function setreports()
    {
        
       $rp_type      =  $this->input->get(self::C_RTYPE); 
       $rp_from_date =  $this->input->get(self::C_FROM_DATE); // Start Date
       $rp_to_date   =  $this->input->get(self::C_TO_DATE); // End Date
       
//       echo $rp_type;
           
       $this->m_report->isreportvalid($rp_type,$rp_from_date,$rp_to_date);  
       if($rp_type === SELF::C_OPEN_SO_REPORT)
       {
           
       $lv_project_name = $this->input->get(self::C_PROJ_NAME);
       $lv_project_bu = $this->input->get(self::C_PROJ_BU);
      $lv_arr_locs = $this->input->get(self::C_PROJ_LOC) ;
       $lv_capability = $this->input->get(self::C_CAPABILITY);
       $lv_proj_id = $this->input->get(self::C_PROJ_ID);
       $lv_cust_name = $this->input->get(self::C_CUST_NAME);
      $lv_type = $this->input->get(self::C_TYPE);
      
       $filtered_so_locs = array_filter($lv_arr_locs); 
       $this->m_open_so->set_attributes($rp_from_date,$rp_to_date,$lv_project_name,$lv_project_bu,$filtered_so_locs,$lv_capability,$lv_proj_id,$lv_cust_name,$lv_type);
         
       }
       $this->m_report->download();       

    }
    
    public function viewreports(){
       $rp_type      =  $this->input->get(self::C_RTYPE); 
       $rp_from_date =  $this->input->get(self::C_FROM_DATE); // Start Date
       $rp_to_date   =  $this->input->get(self::C_TO_DATE); // End Date
       
       
       $this->m_report->isreportvalid($rp_type,$rp_from_date,$rp_to_date);
       
    
       $arr_softlock = $this->m_report->viewreport($rp_from_date,$rp_to_date);
       
       
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arr_softlock,JSON_PRETTY_PRINT));
    }
    
}
//    public function softLocked($from_date, $to_date )
//    {
//        if ( ! file_exists(APPPATH.'/views/pages/'.$page.'.php'))
//        {
//                // Whoops, we don't have a page for that!
//                show_404();
//        }
//
////        $data['title'] = ucfirst($page); // Capitalize the first letter
//
//        $this->load->view('templates/header', $data);
//        $this->load->view('pages/'.$page, $data);
//        $this->load->view('templates/footer', $data);
//    }
//}
