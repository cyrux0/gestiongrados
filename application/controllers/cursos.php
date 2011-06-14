<?php

class Cursos extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
    }

    public function add(){
        $curso = new Curso();
        $action = 'cursos/create';
        $this->load->view('cursos/add', array('page_title' => 'Nuevo Curso', 'data' => array('curso' => $curso, 'action' => $action)));
    }
    
    public function create(){
        $curso = new Curso();
        $curso->fromArray($this->input->post());
        $curso->save();
        redirect('titulaciones/index');
    }
}


/* End of file: cursos.php */
/* Location: ./application/controllers/cursos.php */ 
