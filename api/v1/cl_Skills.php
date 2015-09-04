<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of cl_EmpSkills
 *
 * @author "Prashanth Tellis Prashanth.Tellis@capgemini.com"
 */

require __DIR__.DIRECTORY_SEPARATOR.'cl_DB.php';
class cl_EmpSkills {
    const c_alt_skill_offset = 2;
    const c_alt_skill_prefix = 'alt_skill';
    const c_alt_skill_max_index = 12;
    const C_SKILLS_TABLE    = 'c_emp_skill_matrix';
    
    private $arr_skills_matrix = [];
    
    public function __construct() {
        $this->setSkillMatrix();
    }
    
    private function setSkillMatrix()
    {
        $larr_skills = [];
        $lv_query =  'SELECT'.PHP_EOL
                     .'*'.PHP_EOL
                     .'FROM'.PHP_EOL
                     .self::C_SKILLS_TABLE;
        $lo_db = new cl_DB();
        $larr_skills = $lo_db->getResultsFromQuery($lv_query);
        foreach($larr_skills as $lwa_skill)
        {
             $larr_alt_skills = array_slice($lwa_skill, self::c_alt_skill_offset, self::c_alt_skill_max_index);
             $larr_alt_skills = array_values($larr_alt_skills)
        }
        $this->arr_skill_matrix = $larr_skills;
        
    }
    public function doesEmpSkill_MatchSkillMatrix($fp_v_emp_skill ,$fp_v_so_skill)
    {
        $lv_matches = false;
        foreach($this->arr_skills_matrix as $lwa_skill_matrix)
        {
            if($this->arr_skills_matrix['skill'] = $fp_v_so_skill)
            {
                $larr_alt_skills_assoc = array_slice($lwa_skill_matrix, self::c_alt_skill_offset, self::c_alt_skill_max_index);
                $larr_alt_skills_values =  array_values($larr_alt_skills_assoc);
                
            }
            
        }
    }
}
