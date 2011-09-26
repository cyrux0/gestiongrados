<?php
class Asignaturas extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->asignaturas_table = Doctrine::getTable('Asignatura');
        $this->titulaciones_table = Doctrine::getTable('Titulacion');
        $this->cursos_table = Doctrine::getTable('Curso'); 
        $this -> layout = '';
        $this->notices = '';
        $this->alerts = '';
        $this->modelObject = null;
        $this->_filter(array('add_to', 'create', 'edit', 'update', 'delete', 'add_carga'), array($this, 'authenticate'), 1); // Sólo permitimos a un usuario de tipo administrador (1)
        $this->_filter(array('add_carga'), array($this, 'authenticate'), 2); // Sólo se lo permitimos al usuario de tipo planner (2)
    }

    public function add_to($id) {
        $data['nombre_titulacion'] = $this -> titulaciones_table -> find($id) -> nombre;
        $asignatura = new Asignatura;
        $asignatura -> titulacion_id = $id;
        $action = 'asignaturas/create/' . $asignatura -> id;
        $data['data'] = array('result' => $asignatura, 'action' => $action);
        $data['page_title'] = 'Añadir asignatura';
        $this -> load -> view('asignaturas/add', $data);
    }

    public function create(){
        $this->modelObject = new Asignatura;
        $this->modelObject->fromArray($this->input->post());
        if(!$this->_submit_validate()){
            $this->add_to($this->input->post('titulacion_id'));
        }else{
            $this->modelObject->save();
            $this->notices = 'Asignatura añadida correctamente';
            $this->session->set_flashdata('notices', $this->notices);
            redirect('titulaciones/show/' . $this->input->post('titulacion_id'));
        }
    }

    public function show($id, $id_curso){
        $asignatura = $this->asignaturas_table->find($id);
        $q = Doctrine_Query::create()->select('c.*')->from('PlanDocente c')->where('c.curso_id = ? AND c.asignatura_id = ?', array($id_curso, $id));
        $resultado = $q->fetchArray();
        if($this->input->post('js'))
            unset($this->layout);
        $this->load->view('asignaturas/show', array('carga' => (object) $resultado[0], 'asignatura' => $asignatura));
    }
    
    public function edit($id) {
        $asignatura = $this -> asignaturas_table -> find($id);
        $action = 'asignaturas/update/' . $asignatura -> id;
        $data['data'] = array('result' => $asignatura, 'action' => $action);
        $data['nombre_asignatura'] = $asignatura -> nombre;
        $data['page_title'] = 'Editando asignatura';
        $this -> load -> view('asignaturas/edit', $data);
    }

    public function update($id) {
        $this->modelObject = $this -> asignaturas_table -> find($id);
        $this->modelObject -> fromArray($this -> input -> post());
        if(!$this->_submit_validate()){
            $this->edit($id);
        }else{
            $this->notices = 'Asignatura añadida correctamente';
            $this->session->set_flashdata('notice', $this->notices);
            $this->modelObject->save();
            redirect('titulaciones/show/' . $this->modelObject->titulacion_id);
        }
    }

    public function delete($id) {
        $asignatura = $this -> asignaturas_table -> find($id);
        $titulacion_id = $asignatura -> titulacion_id;
        $asignatura -> delete();
        redirect('titulaciones/show/' . $titulacion_id);
        }

    public function add_carga($asignatura_id){
        $global = new PlanDocente;
        $global->asignatura_id = $asignatura_id;
        $cursos = $this->cursos_table->findAll();
        $options = array();
        foreach($cursos as $curso){
            $options[$curso->id] = date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y");
        }
        $action = 'planesdocentes/create/';
        $data['data'] = array('result' => $global, 'action' => $action);
        $data['nombre_asignatura'] = $this->asignaturas_table->find($asignatura_id)->nombre;
        $data['page_title'] = 'Añadiendo carga global';
        $data['options'] = $options;
        $this->load->view('PlanDocente/add', $data);
    }


    
    private function _submit_validate(){
        $this->form_validation->set_rules('codigo', 'Código', 'required|exact_length[3]|numeric');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]|max_length[200]|alpha_numeric'); // Hay que crear la regla alpha_numeric_ext igual que la alpha_ext pero con números.
        $this->form_validation->set_rules('creditos', 'Créditos', 'required|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('materia', 'Materia', 'required|min_length[3]|max_length[100]|alpha_ext');
        $this->form_validation->set_rules('departamento', 'Departamento', 'required|min_length[3]|max_length[200]|alpha_ext');
        $this->form_validation->set_rules('curso', 'Curso', 'required|is_natural_no_zero');
        
        return $this->form_validation->run() && $this->modelObject->isValid();
    }
}

/* Fin archivo asignaturas.php */