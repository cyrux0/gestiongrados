<?php

class Login extends MY_Controller{
    
    public function index(){
        $this->session->keep_flashdata('notices');
        $this->load->view('login/form');
        $this->layout = '';
    }
    
    public function submit(){
        $url = $this->session->userdata('prevurl') ?: '/' ;
        if($this->_authenticate()){
            redirect($url);
        }else{
            $this->alerts = "Usuario o contraseña incorrecta";
            $this->index();
            return FALSE;
        }
        
        
    }
    
    function _authenticate(){
        return Current_User::login($this->input->post('email'), $this->input->post('password'));
    }
}
