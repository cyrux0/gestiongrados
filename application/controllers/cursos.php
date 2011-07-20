<?php

class Cursos extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->cursos_table = Doctrine::getTable('Curso');
        
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
    
    public function edit($id){
        $curso = $this->cursos_table->find($id);
        $action = 'cursos/update/' . $id;
        $this->load->view('cursos/edit', array('page_title' => 'Editar Curso', 'data' => array('curso' => $curso, 'action' => $action)));
    }
    
    public function update($id){
        $curso = $this->cursos_table->find($id);
        $curso->fromArray($this->input->post());
        $curso->save();
        redirect('titulaciones/index');
    }
}


/* End of file: cursos.php */
/* Location: ./application/controllers/cursos.php */ 
