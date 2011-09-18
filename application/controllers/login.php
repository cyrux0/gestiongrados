<?php

class Login extends CI_Controller{
    
    public function index(){
        $this->load->view('login/form');
        $this->layout = '';
    }
    
    public function submit(){
        
        if($this->_authenticate()){
            redirect('/');
        }else{
            $this->index();
            return FALSE;
        }
        
        
    }
    
    function _authenticate(){
        return Current_User::login($this->input->post('username'), $this->input->post('password'));
    }
}
