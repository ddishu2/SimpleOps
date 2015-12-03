<?php /*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of m_report
 *
 * @author vkhisty
 */
require_once(APPPATH.'libraries/l_methods.php');
require_once(APPPATH.'models/m_lock.php');
require_once(APPPATH.'models/m_amendment.php');
class m_report extends CI_model
{
    const C_HDR_LINE1          = 'Content-Type: text/csv'; 
//    charset=utf-8';
/**
 * @var string C_HDR_LINE2_PREFIX Specifies name of report file to download
 * @example 'Content-Disposition: attachment; filename=abc.csv' .
 * 
 */
    const C_HDR_LINE2_PREFIX   = 'Content-Disposition: attachment; filename= ';
    const C_FILE_LINE1         = 'Lock ID, SO ID, Emp ID, Lock Status, Status Description, Created On, Approved By';
    /**
     * C_HDR_LINE2_SUFFIX File Type
     */
    /**
     * C_DATE_FORMAT DD-MM-YYYY_HH:MM:SS
     */
    const C_RTYPE = 'type';
                
    
    const C_PHP_OUT_STREAM    = 'php://output';
    const C_DATE_FORMAT       = 'd-m-Y_H:i:s';
    const C_MODE_FILE_WRITE   = 'w';
    const C_FILENAME_SUFFIX   = '.csv';
    const C_FILENAME_DELIMITER = '_';
    const C_TYPE_SL         = 'Soft_Lock';
    const C_TYPE_SL_RELEASE = 'Soft_Lock_Release';
    const C_TYPE_HL         = 'Hard_Lock';
    const C_TYPE_HL_RELEASE = 'Hard_Lock_Release';
    CONST C_TYPE_REJ_EMP    = 'Rejected_Employees';
    const C_TYPE_AMMENDMENTS = 'Amendment_Report';
    const C_TYPE_REJ_SO     = 'Rejected_SO';
    const C_EX_INVALID      = 'Invalid Mail Type';
    
    const C_softlock_display = 'v_softlock_display';
 
    
    
    protected $v_report_type;
    protected $v_start_date ;
    protected $v_end_date;
    
    
    /**
     * @throws Invalid Mail Type Exception.
     */
    public function __construct()
    {
        $this->load->database();
          }
   public function isreportvalid($fp_v_report_type,$fp_v_start_date,$fp_v_end_date)
        
    {
   
//    echo $fp_v_report_type;
//    echo $fp_v_start_date;
//    echo $fp_v_end_date;
//    
         $re_valid = false;
        if(     $fp_v_report_type === self::C_TYPE_SL
            ||  $fp_v_report_type === self::C_TYPE_SL_RELEASE
            ||  $fp_v_report_type === self::C_TYPE_HL
            ||  $fp_v_report_type === self::C_TYPE_HL_RELEASE
            ||  $fp_v_report_type == self::C_TYPE_AMMENDMENTS)
        {
            $re_valid = true;
        }
//        echo $re_valid;
        if($re_valid === true){
            $this->set_attributes($fp_v_report_type);
            $this->setdates($fp_v_start_date, $fp_v_end_date);
//            echo self::$v_report_type;
             
        }
    }
//    
  public function set_attributes($fp_report_type)
    {
       $this->v_report_type = $fp_report_type;
       
      
    }
   public function setdates($fp_start_dates,$fp_end_dates){
      

$lv_dates_valid = l_methods::isDateRangeValid($fp_start_dates, $fp_end_dates);
if($lv_dates_valid === true)
{
$this->v_start_date  = $fp_start_dates;
$this->v_end_date    = $fp_end_dates;   
}
else
{
$this->setDefaultDates();
}
} 

public function setDefaultDates() {


$this->v_start_date  = date(cl_methods::C_DATE_FORMAT);
$this->v_end_date = $this->v_start_date;
}


public function download()
{

$this->setHeaders();
/**
////         * $lo_csv_output File ptr connected to PHP O/P stream in write mode.
////         * 
////         */
        $lo_csv_output = fopen(self::C_PHP_OUT_STREAM, self::C_MODE_FILE_WRITE);
//////        $lo_csv_output = fopen("output.csv", self::C_MODE_FILE_WRITE);
////        // output the column headings
//////     fputcsv($lo_csv_output, array('Column 1', 'Column 2', 'Column 3'));
////        
////        
//     fputcsv($lo_csv_output,array('Emp ID','Employee Name','Service Line','Project Code','Project Name','Start date','End date','SO #','SO Level (P0-M7)','T&E approver ID','T&E approver Name','Smart Project Code','FTE%','Tagging Type (expense / effort booking )'));
        

         switch ($this->v_report_type) {
        case self::C_TYPE_HL:
                   fputcsv($lo_csv_output,array('Emp ID','Employee Name','Service Line','Project Code','Project Name','Start date','End date','SO #','SO Level (P0-M7)','T&E approver ID','T&E approver Name','Smart Project Code','FTE%','Tagging Type (expense / effort booking)','Updated By','Time Stamp '));
                    break;
                
//                case self::C_TYPE_HL_RELEASE:
//                
//                    break;
                
                case self::C_TYPE_SL:
                 fputcsv($lo_csv_output,array('Type','Ten digit SO number,SO line number,SO quantity number','Numeric Emp ID'));
                    break;
                
//                case self::C_TYPE_SL_RELEASE:
//                     fputcsv($lo_csv_output,array('ID','Name','Level','IDP','Location','Billing Status','Competancy','current project name','curr start date','current End date','project end date projected','supervisor name','Customer name','Domain ID','New end Date','Action','Roll Off lead time','Extension Notice','new Supervisor Corp ID','New Supervisor ID','New Supervisor Name','Reason','Requested BY','status','comments by ops team','updated on'));
//                                    break;
                
                case self::C_TYPE_AMMENDMENTS:
//                 case   'Amendment_Report':
                   fputcsv($lo_csv_output,array('ID','Name','Level','IDP','Location','Billing Status','Competancy','current project name','curr start date','current End date','project end date projected','supervisor name','Customer name','Domain ID','New end Date','Action','Roll Off lead time','Extension Notice','new Supervisor Corp ID','New Supervisor ID','New Supervisor Name','Reason','Requested BY','status','comments by ops team','updated on'));       
                     break;
                 
                default:
//////
                   break;
           }
////         
////        
        $arr_data = $this->getData();
        for($i=0;$i<count($arr_data);$i++)
        {
           $row = $arr_data[$i]; 
        fputcsv($lo_csv_output,$row);
        }
        // make php send the generated csv lines to the browser
        fpassthru($lo_csv_output);
        fclose($lo_csv_output);
    }
////    
        private function setHeaders() 
        {
            $lv_hdrLine2 = $this->getHeaderLine2();
            header(self::C_HDR_LINE1);
            header($lv_hdrLine2);
            header("Pragma: no-cache");
            header("Expires: 0");
        }
////
////
        private function getData()
        {
            $re_data = [];
             //$re_data = $this->m_lock->gethardlockdata($this->v_start_date,$this->v_end_date);
          // $lo_lock = new cl_Lock();
            switch ($this->v_report_type) 
            {
                case self::C_TYPE_HL:
//                     
                    $re_data = $this->m_lock->gethardlockdata($this->v_start_date,$this->v_end_date);
//////                    
                    break;
////                case self::C_TYPE_HL_RELEASE:
////                
////                    break;
                case self::C_TYPE_SL:
                    
                $re_data = $this->m_lock->getsoftlockdata($this->v_start_date,$this->v_end_date);
//                    $re_data = $this->m_amendment->getamendmentdata($this->v_start_date,$this->v_end_date);
                    break;
//                case self::C_TYPE_SL_RELEASE:
//                    $re_data = $this->m_amendment->getamendmentdata($this->v_start_date,$this->v_end_date);
//                    break;
////                
////                    break;
//                case self::C_TYPE_AMMENDMENTS:
//                    
//                    $re_data = $this->m_amendment->getamendmentdata($this->v_start_date,$this->v_end_date);
//                    
//                    break;
                default:
                    $re_data = [];
                    break;
            }
        return $re_data;
        }
////    
////    /**
////     * 
////     * @return string Content Disposition Header with Name of Report File at end
////     */
    private function getHeaderLine2()
    {
        $lv_filename = $this->getFileName();
        $re_header_line2 =  self::C_HDR_LINE2_PREFIX
                                .$lv_filename;
        return $re_header_line2;
    }
////    
////    /**
////     * 
////     * @return string Name of CSV Report File suffixed by timestamp
////     */
    private function getFileName()
    {
        $re_timestamp = date(self::C_DATE_FORMAT);
        $re_filename  = $this->v_report_type
                       .self::C_FILENAME_DELIMITER
                       .$re_timestamp
                      .self::C_FILENAME_SUFFIX;
        return $re_filename;
    }
    
    public function viewreport($fp_start_date,$fp_end_date){

        switch ($this->v_report_type) 
            {
           case self::C_TYPE_SL: 
        $arr_result = [];
        $this->db->select('so_id,so_proj_id,so_proj_name,lock_start_date,lock_end_date,emp_id,emp_name,`level`,prime_skill,loc,end_date,skill_cat,reason');
        $this->db->from(self::C_softlock_display);
        $this->db->where('updated_on >=',$fp_start_date); 
       $this->db->where('updated_on <=',$fp_end_date);
       $arr_result = $this->db->get();
       $arr_result_final = $arr_result->result_array();     
       return $arr_result_final;
       break;
   
            }
        
    }
}
