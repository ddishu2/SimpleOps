<?php
/**
 * Description of salesOrders
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class SalesOrders extends CI_Controller
{

    public function __construct() 
    {
        parent::__construct();
    }
    public function getOpen()
    {
        echo $this->input->get('so_id');
        echo 'Hello World';
    }

    
    
    
}
