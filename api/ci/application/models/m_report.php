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
require_once(APPPATH.'models/cl_methods.php');
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
    const C_TYPE_REJ_SO     = 'Rejected_SO';
    const C_EX_INVALID      = 'Invalid Mail Type';
    const C_TYPE_AMMENDMENTS = 'Amendment_Report';
    
    
    private $v_report_type = '';
//    private $v_start_date;
//    private $v_end_date;
//    
    
    /**
     * @throws Invalid Mail Type Exception.
     */
    public function __construct()
    {
        
    }
    
public function isreportvalid($fp_v_report_type,$fp_v_start_date,$fp_v_end_date)
    {
         $re_valid = false;
        if(     $fp_v_report_type === self::C_TYPE_SL
            ||  $fp_v_report_type === self::C_TYPE_SL_RELEASE
            ||  $fp_v_report_type === self::C_TYPE_HL
            ||  $fp_v_report_type === self::C_TYPE_HL_RELEASE
            ||  $fp_v_report_type == self::C_TYPE_AMMENDMENTS)
        {
            $re_valid = true;
            $this->setreport_type($fp_v_report_type);
        }
        if($re_valid === true){
          //  $this->setdates($fp_v_start_date, $fp_v_end_date);
        }
    }
    
public function setreport_type($fp_report)
{
    $this->v_report_type = $fp_report;
}
   public function setdates($fp_start_dates,$fp_end_dates){
      
    
        $lv_dates_valid = cl_methods::isDateRangeValid($fp_start_dates, $fp_end_dates);
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
         * $lo_csv_output File ptr connected to PHP O/P stream in write mode.
         * 
         */
        $lo_csv_output = fopen(self::C_PHP_OUT_STREAM, self::C_MODE_FILE_WRITE);
//        $lo_csv_output = fopen("output.csv", self::C_MODE_FILE_WRITE);
        // output the column headings
//     fputcsv($lo_csv_output, array('Column 1', 'Column 2', 'Column 3'));
         fputcsv($lo_csv_output,array('ID','Name','Level'));
        
        $arr_data = $this->getData();
        for($i=0;$i<count($arr_data);$i++)
        {
           $row = $arr_data[$i]; 
        fputcsv($lo_csv_output,$row);
        }
        // make php send the generated csv lines to the browser
//        fpassthru($lo_csv_output);
        fclose($lo_csv_output);
    }
    
        private function setHeaders() 
        {
            $lv_hdrLine2 = $this->getHeaderLine2();
            header(self::C_HDR_LINE1);
            header($lv_hdrLine2);
            header("Pragma: no-cache");
            header("Expires: 0");
        }


        private function getData()
        {
            $re_data = [];
            $lo_lock = new cl_Lock();
            switch ($this->v_report_type) 
            {
                case self::C_TYPE_HL:
                    
                    break;
                case self::C_TYPE_HL_RELEASE:
                
                    break;
                case self::C_TYPE_SL:
                
                    break;
                case self::C_TYPE_SL_RELEASE:
                
                    break;
                case self::C_TYPE_AMMENDMENTS:
//                            $lo_ammendments = new cl_ammendments();
//                           $re_data = $lo_ammendments->getAmmendmentsReport($this->v_start_date,$this->v_end_date);
//                    $re_data1 = [];
                    $re_data['ID'] = '1234';
                    $re_data['Name'] = 'abcd';
                    $re_data['level'] = 'P1';
                    break;
                default:
                    $re_data = [];
                    break;
            }
        return $re_data;
        }
    
    /**
     * 
     * @return string Content Disposition Header with Name of Report File at end
     */
    private function getHeaderLine2()
    {
        $lv_filename = $this->getFileName();
        $re_header_line2 =  self::C_HDR_LINE2_PREFIX
                                .$lv_filename;
        return $re_header_line2;
    }
    
    /**
     * 
     * @return string Name of CSV Report File suffixed by timestamp
     */
    private function getFileName()
    {
        $re_timestamp = date(self::C_DATE_FORMAT);
        $re_filename  = $this->v_report_type
                       .self::C_FILENAME_DELIMITER
                       .$re_timestamp
                      .self::C_FILENAME_SUFFIX;
        return $re_filename;
    }

}
