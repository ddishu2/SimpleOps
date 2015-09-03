<?php
/**
 *A generic class that can filter a 2D associative array.
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */
class cl_dataFilter {
    private   $arr_filters = [];
    private   $arr_dataToBeFiltered = [];
    protected $arr_filteredData = [];
    protected $v_filteredDataCount = 0;
    
    function __construct($fp_arr_dataToBeFiltered) 
    {
        if(!empty($fp_arr_dataToBeFiltered))
        {
            $this->arr_dataToBeFiltered = $fp_arr_dataToBeFiltered;
            $this->v_filteredDataCount  = count($fp_arr_dataToBeFiltered);
        }
    }

/**   
* @return void
*/
    public function addFilter($fp_key, $fp_arr_values)
    {
        if(key_exists($fp_key, $this->arr_dataToBeFiltered))
        {
            $this->arr_filters[$fp_key] = $fp_arr_values;
        }
    }
    
    private function addFilteredDataRow($fp_arr_filteredData)
    {
        $this->arr_filteredData[] = $lwa_dataRowToBeFiltered;
        $this->v_filteredDataCount++;
    }

/**   
* @return array|[]
*/
    public function getFilteredData()
    {
        $larr_filteredData     = [];
        $lv_filteredDataCount  = 0;
        $larr_dataTableToBeFiltered = $this->getDataToBeFiltered();
        if (!empty($this->arr_filters)) 
        {
            foreach ($larr_dataTableToBeFiltered as $lwa_dataRowToBeFiltered) 
            {
                $lv_doesDataRowMatchFilters = $this->doesDataRowMatchFilters($lwa_dataRowToBeFiltered);
                if ($lv_doesDataRowMatchFilters == true) 
                {
                    $this->addFilteredDataRow($lwa_dataRowToBeFiltered);
//                    if($fp_filteredDataMaxCount > 0 && $lv_filteredDataCount == $fp_filteredDataMaxCount)
//                    {
//                        break;
//                    }
                }
            }
        }
        $this->arr_filteredData    = $larr_filteredData;
        $this->v_filteredDataCount = $lv_filteredDataCount;
        return $this->arr_filteredData;
    }
    
    
    private function getDataToBeFiltered()
    {
        $larr_dataToBeFiltered = [];
        if (empty($this->arr_filteredData)) 
        {
            $larr_dataToBeFiltered = $this->arr_dataToBeFiltered;
        } else 
        {
            $larr_dataToBeFiltered = $this->arr_filteredData;
        }
        return $larr_dataToBeFiltered;
    }
    
    private function doesDataRowMatchFilters($fp_arr_dataRowToBeFiltered)
    {
        $lv_rowMatchesAllFilters = true;
        foreach ($this->$arr_filter as $filter_key => $filter_values) 
        {
//            $lv_valueMatchesCurrentFilter = false; //Initialize flag for Filter Match
            $lv_valueToBeMatched = $lwa_dataToBeFiltered[$filter_key];
            $lv_valueMatchesCurrentFilter = in_array($lv_valueToBeMatched, $filter_values);
//              Performs AND to ensure all filters match
            $lv_rowMatchesAllFilters = $lv_rowMatchesAllFilters && $lv_valueMatchesCurrentFilter;
            if($lv_rowMatchesAllFilters == false)
            {
                break;
            }
        }
        return $lv_rowMatchesAllFilters;
    }

/**   
* @return void
*/
    public function resetFilters()
    {
        $this->arr_filters = [];
        $this->arr_filteredData = [];
    }
}
