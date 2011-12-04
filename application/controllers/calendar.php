<?php

class Calendar extends CI_Controller{
    public function index(){
        $this->layout='';
        $prefs = array (
               'show_next_prev'  => TRUE,
               'next_prev_url'   => 'http://localhost/gestgrados/calendar/index'
             );
        $this->load->library('calendar', $prefs);
        $this->load->view('calendar/index');
        
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
