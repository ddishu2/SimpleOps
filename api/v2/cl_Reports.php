<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'cl_Lock.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'cl_abs_QueryBuilder.php';
/**
 * Description of cl_ReportGenerator
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class cl_Reports
{
    const C_HDR_LINE1          = 'Content-Type: text/csv; charset=utf-8';
/**
 * @var string C_HDR_LINE2_PREFIX Specifies name of report file to download
 * @example 'Content-Disposition: attachment; filename=abc.csv' .
 * 
 */
    const C_HDR_LINE2_PREFIX   = 'Content-Disposition: attachment; filename=';
    const C_FILE_LINE1         = 'Lock ID, SO ID, Emp ID, Lock Status, Status Description, Created On, Approved By';
    /**
     * C_HDR_LINE2_SUFFIX File Type
     */
    /**
     * C_DATE_FORMAT DD-MM-YYYY_HH:MM:SS
     */
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
    
    private $v_report_type = '';
    private $v_start_date;
    private $v_end_date;
    
    
    /**
     * @throws Invalid Mail Type Exception.
     */
    public function __construct($fp_v_report_type, $fp_v_start_date, $fp_v_end_date)
    {
        if(!$this->isReportTypeValid($fp_v_report_type))
        {
            throw new Exception(self::C_EX_INVALID);
        }
        else
        {
            $this->v_report_type = $fp_v_report_type;
        }
        $this->setDates($fp_v_start_date, $fp_v_end_date);
    }
    
    /**
     * 
     * @param string $fp_v_report_type
     * @return boolean
     */
    private function isReportTypeValid($fp_v_report_type)
    {
        $re_valid = false;
        if(     $fp_v_report_type === self::C_TYPE_SL
            ||  $fp_v_report_type === self::C_TYPE_SL_RELEASE
            ||  $fp_v_report_type === self::C_TYPE_HL
            ||  $fp_v_report_type === self::C_TYPE_HL_RELEASE)
        {
            $re_valid = true;
        }
        return $re_valid;
    }
    
/**
 * 
 * @param string $fp_v_start_date Date in YYYY-MM-DD format
 * @param string $fp_v_end_date   Date in YYYY-MM-DD format
 */
    private function setDates($fp_v_start_date, $fp_v_end_date)
    {
        $lv_dates_valid = cl_abs_QueryBuilder::isDateRangeValid($fp_v_start_date, $fp_v_end_date);
        if($lv_dates_valid === true)
        {
            $this->v_start_date  = $fp_v_start_date;
            $this->v_end_date    = $fp_v_end_date;   
        }
        else
        {
            $this->_setDefaultDates();
        }
    }
    
    /**
     * Sets Start and End Date to today's date in YYYY-MM-DD format
     */
    
    private function _setDefaultDates()
    {
        $this->v_start_date  = date(cl_abs_QueryBuilder::C_DATE_FORMAT);
        $this->v_end_date = $this->v_start_date;
    }
    
//    public function download()
//    {
//        // output headers so that the file is downloaded rather than displayed
//        header('Content-Type: text/csv; charset=utf-8');
//        header('Content-Disposition: attachment; filename=data.csv');
//    // create a file pointer connected to the output stream
//        $lo_csv_output = fopen('php://output', 'w');
//        // output the column headings
//        fputcsv($lo_csv_output, array('Column 1', 'Column 2', 'Column 3'));
//         
////        // reset the file pointer to the start of the file
////        fseek($lo_csv_output, self::C_START_OF_FILE);
//         // make php send the generated csv lines to the browser
//        fpassthru($lo_csv_output);
//        fclose($lo_csv_output);
//    }

    public function download()
    {
        $this->setHeaders();
        /**
         * $lo_csv_output File ptr connected to PHP O/P stream in write mode.
         * 
         */
        $lo_csv_output = fopen(self::C_PHP_OUT_STREAM, self::C_MODE_FILE_WRITE);
        // output the column headings
        fputcsv($lo_csv_output, array('Column 1', 'Column 2', 'Column 3'));
         // make php send the generated csv lines to the browser
        fpassthru($lo_csv_output);
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
