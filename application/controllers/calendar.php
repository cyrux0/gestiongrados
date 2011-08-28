<?php

class Calendar extends CI_Controller{
    public function index(){
        $this->layout='';
        $this->load->view('calendar/index');
        
    }   
    
    public function create(){
        $calendario = new Calendario;
        $calendario->fromArray($this->input->post());
        if(!$calendario->isValid())
            echo "error";
        else
            echo "bien";
        
    }
}
