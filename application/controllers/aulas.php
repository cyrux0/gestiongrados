<?php

class Aulas extends MY_Controller
{
    function __construct(){
        parent::__construct();
        $this->layout = '';
    }
    
    public function add(){
        $aula = new Aula;
        $this->load->view('aulas/add', array('aula' => $aula));
    }
    
    public function create(){
        $aula = new Aula;
        $aula->fromArray($this->input->post());
        $aula->save();
        redirect('aulas');
    }
    
    public function index(){
        $aulas = Doctrine::getTable('Aula')->findAll();
        $this->load->view('aulas/index', array('aulas' => $aulas));
    }
    
    public function delete($id){
        $aula = Doctrine::getTable('Aula')->find($id);
        $aula->delete();
    }
    
    
}
