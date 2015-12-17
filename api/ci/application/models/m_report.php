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
require_once(APPPATH.'models/m_open_so.php');
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
    const C_ZER0 = '0';
                
    
    const C_PHP_OUT_STREAM    = 'php://output';
    const C_DATE_FORMAT       = 'Y-m-d_H:i:s';
    const C_MODE_FILE_WRITE   = 'w';
    const C_FILENAME_SUFFIX   = '.csv';
    const C_FILENAME_DELIMITER = '_';
    const C_TYPE_SL         = 'Soft_Lock';
    const C_TYPE_SL_RELEASE = 'Soft_Lock_Release';
    const C_TYPE_OPEN_SO    = 'Open_So_Report';
    const C_TYPE_HL         = 'Hard_Lock';
    const C_TYPE_HL_RELEASE = 'Hard_Lock_Release';
    CONST C_TYPE_REJ_EMP    = 'Rejected_Employees';
    const C_TYPE_AMMENDMENTS = 'Amendment_Report';
    const C_TYPE_REJ_SO     = 'Rejected_SO';
    const C_EX_INVALID      = 'Invalid Mail Type';
    
    const C_softlock_display = 'v_softlock_display';
    const C_FNAME_LOCK_START_DATE = 'lock_start_date';
    const c_v_slock_expiry_display ='v_slock_expiry_display';
    
    
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
            ||  $fp_v_report_type == self::C_TYPE_AMMENDMENTS
            ||  $fp_v_report_type == self::C_TYPE_OPEN_SO)
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


$this->v_start_date  = date(l_methods::C_DATE_FORMAT);
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
                
                case self::C_TYPE_HL_RELEASE:
                fputcsv($lo_csv_output,array('Emp ID','Employee Name','Skill','Service Line','Project Code','Project Name','Start date','End date'));
                    break;
                
                case self::C_TYPE_SL:
                 fputcsv($lo_csv_output,array('Type','Ten digit SO number,SO line number,SO quantity number','Numeric Emp ID'));
                    break;
                
                case self::C_TYPE_SL_RELEASE:
                     fputcsv($lo_csv_output,array('So ID','Project Code','Project Name','Start Date','End Date','Emp ID','Emp Name','Level','Skill','Location','Projected End Date','Skill Category','Reason'));
                                    break;
                
                case self::C_TYPE_AMMENDMENTS:
//                 case   'Amendment_Report':
                   fputcsv($lo_csv_output,array('ID','Name','Level','IDP','Location','Billing Status','Competancy','current project name','curr start date','current End date','project end date projected','supervisor name','Customer name','Domain ID','New end Date','Action','Roll Off lead time','Extension Notice','new Supervisor Corp ID','New Supervisor ID','New Supervisor Name','Reason','Requested BY','status','comments by ops team','updated on'));       
                     break;
                 
        case self::C_TYPE_OPEN_SO:
            
             fputcsv($lo_csv_output,array('So ID','Project Code','Project Name','Customer Name','Project Bu','Project Type','Start Date New','Description','Service Line','Skills','Capability','Location','End Date','Create Date','Submit Date','Entered By','Owner','Region','Level','Status'));
                    break;
                
                default:
//////
                   break;
           }
////         
////        
        $arr_data = $this->getData();
        
        switch ($this->v_report_type)
        {
             case self::C_TYPE_OPEN_SO:
              foreach ($arr_data as $key => $value) {
            
            fputcsv($lo_csv_output,$value);
        }
        
           default :
               for($i=0;$i<count($arr_data);$i++)
        {
        $row = $arr_data[$i]; 
        fputcsv($lo_csv_output,$row);
        }
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
//            echo $this->v_report_type;
            switch ($this->v_report_type) 
            {
                case self::C_TYPE_HL:
//                     
                    $re_data = $this->m_lock->gethardlockdata($this->v_start_date);
                    
//                    print_r($re_data);
//////                    
                    break;
                
                case self::C_TYPE_HL_RELEASE:
                $re_data = $this->m_lock->gethardlockreleasedata($this->v_start_date,$this->v_end_date);
                    break;
                
                case self::C_TYPE_SL:
                    
                $re_data = $this->m_lock->getsoftlockdata($this->v_start_date,$this->v_end_date);
//                    $re_data = $this->m_amendment->getamendmentdata($this->v_start_date,$this->v_end_date);
                    break;
                
                case self::C_TYPE_SL_RELEASE:
                    $re_data = $this->m_lock->getDataSlockExpired($this->v_start_date,$this->v_end_date);
                    break;
////                
////                    break;
                case self::C_TYPE_AMMENDMENTS:
                    
                    $re_data = $this->m_amendment->getamendmentdata($this->v_start_date,$this->v_end_date);
                    
//                    print_r($re_data);
                    break;
                
                case self::C_TYPE_OPEN_SO:
            
             $re_data = $this->m_open_so->get();
                    
//             print_r($re_data);
             
                    break;
                
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
       
           case self::C_TYPE_SL_RELEASE:
            $lv_date = date('Y-m-d');
            $this->db->select('so_id,so_proj_id,so_proj_name,lock_start_date,lock_end_date,emp_id,emp_name,level,prime_skill,loc,end_date,skill_cat,reason,aging');
            $this->db->from(self::c_v_slock_expiry_display);
            $this->db->where(self::C_FNAME_LOCK_START_DATE." BETWEEN CAST('$fp_start_date' AS DATE)AND CAST('$fp_end_date' AS DATE)");
            $lt_data = $this->db->get();
            $lt_result = $lt_data->result_array();
            $lv_count = count($lt_result);
            for($i=0;$i<$lv_count;$i++){
                $start_date = $lt_result[$i]['lock_start_date'];
                $datetime1 = date_create($start_date);
                $datetime2 = date_create($lv_date);
                $interval = date_diff($datetime1, $datetime2);
                $days1 = $interval->format('%R%a days');
                $days = (int)$days1;
                $lt_result[$i]['aging'] = $days;
                
                }
            return $lt_result;
            break;
   
            }
        
    }
}
