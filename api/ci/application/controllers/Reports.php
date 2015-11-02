<?php
/**
 * Description of Reports
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class Reports extends CI_Controller
{

    public function __construct() 
    {
        parent::__construct();
    }
    public function softLocked($from_date, $to_date )
    {
        if ( ! file_exists(APPPATH.'/views/pages/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

//        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);
    }
}
