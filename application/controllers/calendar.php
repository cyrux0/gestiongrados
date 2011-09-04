<?php

class Calendar extends CI_Controller{
    public function index(){
        $this->layout='';
        
        $this->load->view('calendar/index', array('fecha_alt' => ''));
        
    }   
    
    public function create(){
        echo $this->input->post('fecha_alt');
        
    }
    
    public function edit(){
        $this->layout='';
        $vector = array('fecha_alt' => '2010-08-18');//, 'fecha_alt' => '');
        $this->load->view('calendar/index', $vector);
    }
}
