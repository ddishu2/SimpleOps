<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_ReportGenerator
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class cl_ReportGenerator {
    const C_HDR_LINE1          = 'Content-Type: text/csv; charset=utf-8';
/**
 * example 'Content-Disposition: attachment; filename=abc.csv'
 */
    const C_HDR_LINE2_PREFIX   = 'Content-Disposition: attachment; filename=';
    const C_FILE_LINE1         = 'Lock ID, SO ID, Emp ID, Lock Status, Status Description, Created On, Approved By';
    /**
     * C_HDR_LINE2_SUFFIX File Type
     */
    /**
     * C_DATE_FORMAT DD-MM-YYYY_HH:MM:SS
     */
    const C_DATE_FORMAT       = 'd-m-Y_H:i:s';
    const C_FILENAME_SUFFIX      = '.csv';
    const C_TYPE_SL         = 'SoftLock';
    const C_FILENAME_DELIMITER = '_';
    const C_TYPE_SL_RELEASE = 'SoftLockRelease';
    const C_TYPE_HL         = 'HardLock';
    const C_TYPE_HL_RELEASE = 'HardLockRelease';
    const C_EX_INVALID    = 'Invalid Mail Type';
    
    private $v_header_line2 = self::C_HDR_LINE2_PREFIX;
    
    private $v_report_type = '';
    
    
    private function setHeaderLine2()
    {
        $lv_filename = $this->getFileName();
        $this->v_header_line2 =  self::C_HDR_LINE2_PREFIX
                                .$lv_filename;
    }
    
    private function getFileName()
    {
        $re_timestamp = date(self::C_DATE_FORMAT);
        $re_filename  = $this->v_report_type
                       .self::C_FILENAME_DELIMITER
                       .$re_timestamp
                       .self::C_FILENAME_SUFFIX;
        return $re_filename;
    }
    
    /**
     * @throws Invalid Mail Type Exception.
     */
    public function __construct($fp_v_report_type)
    {
        if(!$this->isValid($fp_v_report_type))
        {
            throw new Exception(self::C_EX_INVALID);
        }
        $this->v_report_type = $fp_v_report_type;
        $this->setFileName();
        $this->setHeaderLine2();
    }
    
    private function getData()
    {
        
        
    }
    
    
    public function download()
    {
        
        
    }
    /**
     * 
     * @param string $fp_v_report_type
     * @return boolean
     */
    private function isValid($fp_v_report_type)
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
}
