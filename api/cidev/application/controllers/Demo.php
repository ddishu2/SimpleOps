<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Demo extends CI_Controller
 {
    
    public function __construct() 
    {
       parent::__construct();
        $this->load->model('m_open_so');
        $this->load->model('m_proposals');
        $this->load->model('m_BuEmployees');
        $this->load->model('m_lock');
        $this->load->model('m_SOEmpSkillMatcher');
    }
    
    public function testskill()
    {
        $lv_result=$this->m_SOEmpSkillMatcher->fetchEmpSkillMatrix();
          $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));
    }
    
    public function testskillxref()
    {
        $lv_result=$this->m_SOEmpSkillMatcher->fetch_SO_Emp_Skill_Xref();
          $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));
    }
    
    public function setskill()
    {
        $lv_result=$this->m_SOEmpSkillMatcher->getEmpSkillMatrix();
          $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));
    }
    
     public function setskillxref()
    {
        $lv_result=$this->m_SOEmpSkillMatcher->getSOEmpSkillXref();
          $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));
    }
    
    public function perfectmatch()
    {
        
        $lv_v_so_skill =  $this->input->get(m_SOEmpSkillMatcher::C_SO_SKILL);
        $lv_v_emp_skill = $this->input->get(m_SOEmpSkillMatcher::C_EMP_SKILL);
        
        $lv_result=$this->m_SOEmpSkillMatcher->isPerfectMatch($lv_v_so_skill,$lv_v_emp_skill);
          $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));
    }
    
    public function alternatematch()
    {
        
        $lv_v_so_skill =  $this->input->get(m_SOEmpSkillMatcher::C_SO_SKILL);
        $lv_v_emp_skill = $this->input->get(m_SOEmpSkillMatcher::C_EMP_SKILL);
        
        $lv_result=$this->m_SOEmpSkillMatcher->isMatchOrAlternative($lv_v_so_skill,$lv_v_emp_skill);
          $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($lv_result,JSON_PRETTY_PRINT));
    }
    
  }