<?php

class Cursos extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->cursos_table = Doctrine::getTable('Curso');
        $this->alerts = '';
        $this->notices = '';
        $this->modelObject = '';
    }

    public function add(){
        $curso = new Curso();
        $action = 'cursos/create';
        $this->load->view('cursos/add', array('page_title' => 'Nuevo Curso', 'data' => array('curso' => $curso, 'action' => $action)));
    }
    
    public function create(){
        $this->modelObject = new Curso();
        $this->modelObject->fromArray($this->input->post());

        if($this->modelObject->inicio_semestre1 and $this->modelObject->fin_semestre1){        
            $diferencia1 = date_diff(date_create($this->modelObject->inicio_semestre1), date_create($this->modelObject->fin_semestre1));
            $this->modelObject->num_semanas_semestre1 = ceil($diferencia1->d / 7);
        }
        if($this->modelObject->inicio_semestre2 and $this->modelObject->fin_semestre2){
            $diferencia2 = date_diff(date_create($this->modelObject->inicio_semestre2), date_create($this->modelObject->fin_semestre2));
            $this->modelObject->num_semanas_semestre2 = ceil($diferencia2->d / 7);
        }
        

        if($this->_submit_validate() == FALSE){
            $this->add();
        }else{
            $this->modelObject->save();
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
        if($this->_submit_validate() == FALSE or !$curso->isValid()){
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
    
    private function _submit_validate(){
        $this->form_validation->set_rules('num_semanas_teoria', 'Número de semanas de teoría', 'required|is_natural');
        $this->form_validation->set_rules('num_semanas_semestre1', 'Número de semanas del primer semestre', 'required|is_natural');
        $this->form_validation->set_rules('num_semanas_semestre2', 'Número de semanas del segundo semestre', 'required|is_natural');
        $this->form_validation->set_rules('horas_por_credito', 'Número de horas por crédito', 'required|is_natural');
        $this->form_validation->set_rules('inicio_semestre1', 'Fecha de inicio del primer semestre', 'required|callback__doctrine_validation[inicio_semestre1]');
        $this->form_validation->set_rules('fin_semestre1', 'Fecha de finalización del primer semestre', 'required');
        $this->form_validation->set_rules('inicio_semestre2', 'Fecha de inicio del segundo semestre', 'required');
        $this->form_validation->set_rules('fin_semestre2', 'Fecha de finalización del segundo semestre', 'required');
        $this->form_validation->set_rules('inicio_examenes_enero', 'Fecha de inicio de los exámenes de enero', 'required');
        $this->form_validation->set_rules('fin_examenes_enero', 'Fecha de finalización de los exámenes de enero', 'required');
        $this->form_validation->set_rules('inicio_examenes_junio', 'Fecha de inicio de los exámenes de junio', 'required');
        $this->form_validation->set_rules('fin_examenes_junio', 'Fecha de finalización de los exámenes de junio', 'required');
        $this->form_validation->set_rules('inicio_examenes_sept', 'Fecha de inicio de los exámenes de septiembre', 'required');
        $this->form_validation->set_rules('fin_examenes_sept', 'Fecha de finalización de los exámenes de septiembre', 'required');
        
        return $this->form_validation->run() ;//&& $this->modelObject->isValid();
    }

     function _doctrine_validation($value, $field){
        $this->modelObject->isValid();
        $error_stack = $this->modelObject->getErrorStack();
        $errors = $error_stack->get($field);
        if(count($errors) > 0)
        {
            foreach($errors as $error){
                $this->form_validation->set_message('_doctrine_validation', $error);
            }
            return FALSE;
        }
        else
        {
            return TRUE;
        }
        
    }
}


/* End of file: cursos.php */
/* Location: ./application/controllers/cursos.php */ 
