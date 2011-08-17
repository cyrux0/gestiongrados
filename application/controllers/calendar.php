<?php

class Calendar extends CI_Controller{
    public function index(){
        $this->layout='';
        $this->load->view('calendar/index');
        
    }   
}
