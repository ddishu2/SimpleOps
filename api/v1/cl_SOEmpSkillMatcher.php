<?php
/**
 * 1. Each skill requested by an SO is mapped to a corresponding employee skill 
 * to align nomenclature differences between skills in SO Master and in Emp. Master
 * 2. An employee skill can have upto 10 alternatives which are equivalent to that skill.
 * 3. A Skill requested by an SO can therefore be fulfilled by it's Emp. Skill counterpart (perfect match)or
 * by any of it's Emp. Skill counterpart's alternative skills(alternative match)
 
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */


class cl_SOEmpSkillMatcher 
{
    const C_SO_SKILL_FNAME                = 'so_skill';
    const C_EMP_SKILL_FNAME               = 'emp_skill';
    const C_ALTERNATE_SKILLS_COUNT         = 10;
    const C_ALT_SKILL_MIN_INDEX           = 3;
    private static $c_alt_skill_max_index = 12;
    
    const C_EMP_SKILLS_TABLE        = 'c_emp_skill_matrix';
    const C_SO_EMP_SKILL_XREF_TABLE = 'c_so_emp_skill_xref';
    
    
    private $o_dbhandle;
    private $arr_emp_skills_matrix = [];
    private $arr_so_emp_skill_xref = []; 
   
    
    
    public function __construct() {
        
        
        $this->o_dbhandle = new cl_DB();
        $this->setEmpSkillMatrix();
        $this->set_SO_Emp_Skill_Xref();
//        $this->o_dbhandle->closeDBHandle();
    }
    
    
//    private function getAlternativeSkillMinAndMaxIndex(array &$fp_arr_emp_skills_matrix)
//    {
//        $lv_first_alt_skill = 'alt_skill1';
//        $lv_last_alt_skill  = 'alt_skill10';
//        $larr_keys = array_keys($fp_arr_emp_skills_matrix);
//        echo 'Keys'.json_encode($larr_keys,JSON_PRETTY_PRINT);
//        $lv_alt_skill_min_index = array_search($lv_first_alt_skill,$larr_keys);
//        $lv_alt_skill_max_index = array_search($lv_last_alt_skill, $larr_keys);
//        
//        $this->v_alt_skill_min_index = $lv_alt_skill_min_index;
//        $this->v_alt_skill_max_index = $lv_alt_skill_max_index;
//        echo 'Min:'.$lv_alt_skill_min_index.'Max:'.$lv_alt_skill_max_index;
//    }
//    
//    private function getAlternativeSkillMinAndMaxIndex(array &$fp_arr_emp_skills_matrix)
//    {
//        $lv_first_alt_skill = 'alt_skill1';
//        $lv_last_alt_skill  = 'alt_skill10';
//        $larr_keys = array_keys($fp_arr_emp_skills_matrix);
//        echo 'Keys'.json_encode($larr_keys,JSON_PRETTY_PRINT);
//        $lv_alt_skill_min_index = array_search($lv_first_alt_skill,$larr_keys);
//        $lv_alt_skill_max_index = array_search($lv_last_alt_skill, $larr_keys);
//        
//        $this->v_alt_skill_min_index = $lv_alt_skill_min_index;
//        $this->v_alt_skill_max_index = $lv_alt_skill_max_index;
//        echo 'Min:'.$lv_alt_skill_min_index.'Max:'.$lv_alt_skill_max_index;
//    }
    
    public function getEmpSkillMatrix()
    {
        return $this->arr_emp_skills_matrix;
    }
    
    public function getSOEmpSkillMatrix()
    {
        return $this->arr_so_emp_skill_xref;
    }
    
    /**
     * 
     */
    private function setEmpSkillMatrix()
    {
        $larr_emp_skills_matrix = [];
        $larr_skills = $this->fetchEmpSkillMatrix();
//        $this->setAlternativeMinAndMaxIndex($larr_skills);
//        echo 'Emp Skills array'.json_encode($larr_skills,JSON_PRETTY_PRINT).PHP_EOL;
        foreach($larr_skills as $lwa_skill)
        {
//            echo 'Emp Skills WA'.json_encode($lwa_skill,JSON_PRETTY_PRINT).PHP_EOL;
            $lv_prime_skill = $lwa_skill[self::C_EMP_SKILL_FNAME]; 
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
//        echo 'SO Emp Skills array'.json_encode($larr_so_emp_skills_matrix,JSON_PRETTY_PRINT).PHP_EOL;
        $v_skill_count = count($larr_so_emp_skills_matrix);
        for($v_skill_index = 0; $v_skill_index < $v_skill_count; $v_skill_index++)
        {
            $lwa_so_emp_skill = $larr_so_emp_skills_matrix[$v_skill_index];
            $lv_so_skill  = $lwa_so_emp_skill[self::C_SO_SKILL_FNAME]; 
            $lv_emp_skill = $lwa_so_emp_skill[self::C_EMP_SKILL_FNAME];
//            echo 'SO Skill->'.$lv_so_skill.'---'.'Emp Skill->'.$lv_emp_skill;
            $larr_so_emp_skills_xref[$lv_so_skill] =  $lv_emp_skill;            
        }
        
//        Isn't working for some reason!!!
//        foreach($larr_so_emp_skills_matrix as $key => $lwa_so_emp_skill);
//        {
//            echo 'WA'.json_encode($lwa_so_emp_skill).PHP_EOL;
//            $lv_so_skill  = $lwa_so_emp_skill[self::C_SO_SKILL_FNAME]; 
//            $lv_emp_skill = $lwa_so_emp_skill[self::C_EMP_SKILL_FNAME];
//            echo 'SO Skill->'.$lv_so_skill.'---'.'Emp Skill->'.$lv_emp_skill;
//            $larr_so_emp_skills_xref[$lv_so_skill] =  $lv_emp_skill;
//        }
        $this->arr_so_emp_skill_xref = $larr_so_emp_skills_xref;
//        echo json_encode($larr_so_emp_skills_xref, JSON_PRETTY_PRINT);
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
    public function isPerfectMatch($fp_v_so_skill = '',$fp_v_emp_skill = '' )
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
 * Returns true if emp skill is an alternate to requested SO skill
 * @param string $fp_v_emp_skill
 * @param string $fp_v_so_skill
 * @return boolean
 */
    public function isAlternative($fp_v_so_skill = '', $fp_v_emp_skill = '')
    {
        $lv_alternative = false;
//        echo 'isAltenative'.PHP_EOL;
//        echo 'SO Emp Skills Xref: '.json_encode($this->arr_so_emp_skill_xref,JSON_PRETTY_PRINT).PHP_EOL;
//        echo 'Emp. Altenatives  '.json_encode($this->arr_emp_skills_matrix, JSON_PRETTY_PRINT).PHP_EOL;
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
     * Returns true if emp skill matches requested SO skill or is an alternate to
     * requested SO skill
     * @param type $fp_v_emp_skill
     * @param type $fp_v_so_skill
     * @return type
     */
    public function isMatchOrAlternative($fp_v_so_skill = '' ,$fp_v_emp_skill = '')
    {
        $lv_match_or_alternative = false;
        $lv_match = $this->isPerfectMatch($fp_v_so_skill, $fp_v_emp_skill);
//        echo "Perfect Match"; var_dump($lv_match);
        $lv_alternative = $this->isAlternative($fp_v_so_skill, $fp_v_emp_skill);        
//      echo "Alternative Match";var_dump($lv_alternative);
        $lv_match_or_alternative = $lv_match || $lv_alternative;
        return $lv_match_or_alternative;
    }
}
