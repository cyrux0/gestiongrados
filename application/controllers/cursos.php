<?php

class Cursos extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->cursos_table = Doctrine::getTable('Curso');
        $this->alerts = '';
        $this->notices = '';
    }

    public function add(){
        $curso = new Curso();
        $action = 'cursos/create';
        $this->load->view('cursos/add', array('page_title' => 'Nuevo Curso', 'data' => array('curso' => $curso, 'action' => $action)));
    }
    
    public function create(){
        $curso = new Curso();
        $curso->fromArray($this->input->post());
        if($curso->inicio_semestre1 and $curso->fin_semestre1){        
            $diferencia1 = date_diff(date_create($curso->inicio_semestre1), date_create($curso->fin_semestre1));
            $curso->num_semanas_semestre1 = ceil($diferencia1->d / 7);
        }
        if($curso->inicio_semestre2 and $curso->fin_semestre2){
            $diferencia2 = date_diff(date_create($curso->inicio_semestre2), date_create($curso->fin_semestre2));
            $curso->num_semanas_semestre2 = ceil($diferencia2->d / 7);
        }
        
        if(!$curso->isValid()){
            $this->alerts = $curso->getErrorStackAsString();
            $this->add();
        }else{
            $curso->save();
            $this->notices = 'Curso añadido correctamente';
            $this->session->set_flashdata('notices', $this->notices);
            redirect('cursos/index');
        }
    }
    
    public function edit($id){
        // Aquí habría que modificar la forma de mostrar las fechas, ya que se muestran en el form en formato Y-m-d
        $curso = $this->cursos_table->find($id);
        $action = 'cursos/update/' . $id;
        $this->load->view('cursos/edit', array('page_title' => 'Editar Curso', 'data' => array('curso' => $curso, 'action' => $action)));
    }
    
    public function update($id){
        $curso = $this->cursos_table->find($id);
        $curso->fromArray($this->input->post());
        if($curso->inicio_semestre1 and $curso->fin_semestre1){        
            $diferencia1 = date_diff(date_create($curso->inicio_semestre1), date_create($curso->fin_semestre1));
            $curso->num_semanas_semestre1 = ceil($diferencia1->d / 7);
        }
        if($curso->inicio_semestre2 and $curso->fin_semestre2){
            $diferencia2 = date_diff(date_create($curso->inicio_semestre2), date_create($curso->fin_semestre2));
            $curso->num_semanas_semestre2 = ceil($diferencia2->d / 7);
        }
        if(!$curso->isValid()){
            $this->alerts = $curso->getErrorStackAsString();
            $this->edit($id);
        }else{
            $this->notices = 'Curso actualizado correctamente';
            $this->session->set_flashdata('notices', $this->notices);
            $curso->save();
            redirect('cursos/index');
        }
    }

    public function index(){
        $cursos = $this->cursos_table->findAll();
        $this->load->view('cursos/index', array('cursos' => $cursos));
    }

    public function delete($id){
        $cursos = $this->cursos_table->find($id);
        $cursos->eventos->delete();
        $cursos->planesdocentes->delete();
        $cursos->delete();
        redirect('cursos/index');
    }
}


/* End of file: cursos.php */
/* Location: ./application/controllers/cursos.php */ 
