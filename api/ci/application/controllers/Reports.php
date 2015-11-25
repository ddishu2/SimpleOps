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
    
    public function __construct() 
    {
        parent::__construct();
        
        $this->load->model('m_report');
        $this->load->model('m_lock');
    }
    
    public function setreports()
    {
        
       $rp_type      =  $this->input->get(self::C_RTYPE); 
       $rp_from_date =  $this->input->get(self::C_FROM_DATE); // Start Date
       $rp_to_date   =  $this->input->get(self::C_TO_DATE); // End Date
       
       
       $this->m_report->isreportvalid($rp_type,$rp_from_date,$rp_to_date);
       $this->m_report->download();
        
        

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
