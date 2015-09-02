<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cl_dataFilter
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class cl_dataFilter {
    private   $arr_filter = [];
    private   $v_isFilterSet =  false;
    private   $arr_dataToBeFiltered = [];
    protected $arr_filteredData = [];
    protected $v_filteredDataCount = 0;
    
    function __construct($fp_arr_dataToBeFiltered) 
    {
        
    }
    
    public function addFilter($fp_key, $fp_arr_values)
    {
        if(key_exists($fp_key, $this->arr_dataToBeFiltered))
        {
            $this->arr_filter[$fp_key] = $fp_arr_values;
            $this->v_isFilterSet = true;
        }
    }
    
    public function getFilteredData()
    {
        $larr_dataToBeFiltered = [];
        $larr_filteredData     = [];
        
        if ($this->v_isFilterSet == true) {
            if (empty($this->arr_filteredData)) {
                $larr_dataToBeFiltered = $this->arr_dataToBeFiltered;
            } else {
                $larr_dataToBeFiltered = $this->arr_filteredData;
            }
            foreach ($larr_dataToBeFiltered as $lwa_dataToBeFiltered) {
                $lv_valueMatchesAllFilters = true;
                foreach ($this->$arr_filter as $filter_key => $filter_values) {
                    $lv_valueMatchesCurrentFilter = false; //Initialize flag for Filter Match
                    $lv_valueToBeFiltered = $lwa_dataToBeFiltered[$filter_key];
                    $lv_valueMatchesCurrentFilter = in_array($lv_valueToBeFiltered, $filter_values);

//              Performs AND to ensure all filters match
                    $lv_valueMatchesAllFilters = $lv_valueMatchesAllFilters && $lv_valueMatchesCurrentFilter;
                }
                if ($lv_valueMatchesAllFilters == true) {
                    $larr_filteredData[] = $lwa_dataToBeFiltered;
                }
            }
        }
        $this->arr_filteredData = $larr_filteredData;
        return $this->arr_filteredData;
    }
    
    
    private function doesDataRowMatchFilter($fp_arr_dataRowToBeFiltered)
    {
        $lv_rowMatchesAllFilters = true;
        foreach ($this->$arr_filter as $filter_key => $filter_values) 
        {
            $lv_valueMatchesCurrentFilter = false; //Initialize flag for Filter Match
            $fp_arr_dataToBeFiltered = $lwa_dataToBeFiltered[$filter_key];
            $lv_valueMatchesCurrentFilter = in_array($fp_arr_dataToBeFiltered, $filter_values);

//              Performs AND to ensure all filters match
                    $lv_rowMatchesAllFilters = $lv_rowMatchesAllFilters && $lv_valueMatchesCurrentFilter;
        }
    }
    
    public function resetFilters()
    {
        $this->arr_filter = [];
    }
    
}
