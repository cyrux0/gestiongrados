<?php

class Administracion extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
    }
    
    public function index(){
        $this->load->view('administracion/index');
    }
}

/* End of file administracion.php */
/* Location: ./application/controllers/administracion.php */