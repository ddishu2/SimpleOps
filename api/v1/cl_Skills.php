<?php
/**
 * Description of cl_EmpSkills
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */

require __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';

class cl_Skills {
    const C_ALT_SKILL_MIN_INDEX     = 2;
    const C_ALT_SKILL_COUNT         = 10;
    const C_ALT_SKILL_PREFIX        = 'alt_skill';
    const C_SO_SKILL_FNAME          = 'so_skill';
    const C_EMP_SKILL_FNAME         = 'emp_skill';
    const C_EMP_SKILLS_TABLE        = 'c_emp_skill_matrix';
    const C_SO_EMP_SKILL_XREF_TABLE = 'c_so_emp_skill_xref';
    private static $c_alt_skill_max_index = self:: C_ALT_SKILL_MIN_INDEX + self::C_ALT_SKILL_COUNT - 1 ;
    private $o_dbhandle;
            
    private $arr_emp_skills_matrix = [];
    private $arr_so_emp_skill_xref = []; 
    
    public function __construct() {
        $this->o_dbhandle = new cl_DB();
        $this->setEmpSkillMatrix();
        $this->set_SO_Emp_Skill_Xref();
    }
    /**
     * 
     */
    private function setEmpSkillMatrix()
    {
        $larr_emp_skills_matrix = [];
        $larr_skills = $this->fetchEmpSkillMatrix();
        foreach($larr_skills as $lwa_skill)
        {
            $lv_prime_skill = $lwa_skill(self::C_EMP_SKILL_FNAME); 
            $larr_alt_skills = array_slice($lwa_skill, self::C_ALT_SKILL_MIN_INDEX, self::$c_alt_skill_max_index);
            $larr_alt_skills = array_values($larr_alt_skills);
            $larr_emp_skills_matrix[$lv_prime_skill] =  $larr_alt_skills;
        }
        $this->arr_emp_skills_matrix = $larr_emp_skills_matrix;
        
    }
    /**
     * 
     * @return array
     */
    private function fetchEmpSkillMatrix()
    {
        $larr_skills = [];
        $lv_query =  'SELECT'.PHP_EOL
                     .'*'.PHP_EOL
                     .'FROM'.PHP_EOL
                     .self::C_EMP_SKILLS_TABLE;
        $larr_skills = $this->o_dbhandle->getResultsFromQuery($lv_query);
        return $larr_skills;
    }
    
    /**
     * 
     */
    private function set_SO_Emp_Skill_Xref()
    {
        $larr_so_emp_skills_xref = [];
        $larr_so_emp_skills_matrix = $this->fetch_SO_Emp_Skill_Xref();
        foreach($larr_so_emp_skills_matrix as $lwa_so_emp_skill);
        {
            $lv_so_skill  = $lwa_so_emp_skill(self::C_SO_SKILL_FNAME); 
            $lv_emp_skill = $lwa_so_emp_skill(self::C_EMP_SKILL_FNAME);
            $larr_so_emp_skills_xref[$lv_so_skill] =  $lv_emp_skill;
        }
        $this->arr_so_emp_skill_xref = $larr_so_emp_skills_xref;
    }
    
    /**
     * 
     * @return array
     */

    private function fetch_SO_Emp_Skill_Xref()
    {
        $larr_so_emp_skill_xref = [];
        $lv_query =  'SELECT'.PHP_EOL
                     .'*'.PHP_EOL
                     .'FROM'.PHP_EOL
                     .self::C_SO_EMP_SKILL_XREF_TABLE;
       
        $larr_so_emp_skill_xref = $this->o_dbhandle->getResultsFromQuery($lv_query);
        return $larr_so_emp_skill_xref;
    }
    
    /**
     * 
     * @param string $fp_v_emp_skill
     * @param string $fp_v_so_skill
     * @return boolean
     */
    public function isPerfectMatch($fp_v_emp_skill = '', $fp_v_so_skill = '')
    {
        $lv_matches = false;
        if(array_key_exists($fp_v_so_skill, $this->arr_so_emp_skill_xref))
        {
            if($this->arr_so_emp_skill_xref[$fp_v_so_skill] === $fp_v_emp_skill)
            {
                $lv_matches = true;
            }
        }
        return $lv_matches;
    }
    
/**
 * Checks if emp skill is an alternate to requested SO skill
 * @param string $fp_v_emp_skill
 * @param string $fp_v_so_skill
 * @return boolean
 */
    private function isAlternative($fp_v_emp_skill = '', $fp_v_so_skill = '')
    {
        $lv_alternative = false;
        if(array_key_exists($fp_v_so_skill, $this->arr_so_emp_skill_xref))
        {
            $lv_so_xref_emp_skill        = $this->arr_so_emp_skill_xref[$fp_v_so_skill];
            $larr_so_xref_emp_alt_skills = $this->arr_emp_skills_matrix[ $lv_so_xref_emp_skill]; 
            if(in_array($fp_v_emp_skill, $larr_so_xref_emp_alt_skills, true))
            {
                $lv_alternative = true;
            }
        }
        return $lv_alternative; 
        
    }

    /**
     * 
     * @param type $fp_v_emp_skill
     * @param type $fp_v_so_skill
     * @return type
     */
    public function isMatchOrAlternative($fp_v_emp_skill = '' ,$fp_v_so_skill = '')
    {
        $lv_match = $this->isPerfectMatch($fp_v_emp_skill, $fp_v_so_skill);
        $lv_alternative = $this->isAlternative($fp_v_emp_skill, $fp_v_so_skill);
        
        $lv_match_or_alternative = $lv_match || $lv_alternative;
        
        return $lv_match_or_alternative;
    }
}
