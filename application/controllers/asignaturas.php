<?php
class Asignaturas extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->asignaturas_table = Doctrine::getTable('Asignatura');
        $this->titulaciones_table = Doctrine::getTable('Titulacion');
        $this->cursos_table = Doctrine::getTable('Curso'); 
        $this -> layout = '';
        $this->notices = '';
        $this->alerts = '';
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
        $asignatura = new Asignatura;
        $asignatura->fromArray($this->input->post());
        if(!$asignatura->isValid()){
            $this->alerts = $asignatura->getErrorStackAsString();
            $this->add_to($this->input->post('titulacion_id'));
        }else{
            $asignatura->save();
            $this->notices = 'Asignatura añadida correctamente';
            $this->session->set_flashdata('notice', $this->notices);
            redirect('titulaciones/show/' . $this->input->post('titulacion_id'));
        }
    }

    public function show($id, $id_curso){
        $asignatura = $this->asignaturas_table->find($id);
        $q = Doctrine_Query::create()->select('c.*')->from('CargaGlobal c')->where('c.curso_id = ? AND c.asignatura_id = ?', array($id_curso, $id));
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
        $asignatura = $this -> asignaturas_table -> find($id);
        $asignatura -> fromArray($this -> input -> post());
        if(!$asignatura->isValid()){
            $this->alerts = $asignatura->getErrorStackAsString();    
            $this->edit($id);
        }else{
            $this->notices = 'Asignatura añadida correctamente';
            $this->session->set_flashdata('notice', $this->notices);
            $asignatura -> save();
            redirect('titulaciones/show/' . $asignatura -> titulacion_id);
        }
    }

    public function delete($id) {
        $asignatura = $this -> asignaturas_table -> find($id);
        $titulacion_id = $asignatura -> titulacion_id;
        $asignatura -> delete();
        redirect('titulaciones/show/' . $titulacion_id);
        }

    public function add_carga($asignatura_id){
        $global = new CargaGlobal;
        $global->asignatura_id = $asignatura_id;
        $cursos = $this->cursos_table->findAll();
        $options = array();
        foreach($cursos as $curso){
            $options[$curso->id] = date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y");
        }
        $action = 'asignaturas/create_carga/';
        $data['data'] = array('result' => $global, 'action' => $action);
        $data['nombre_asignatura'] = $this->asignaturas_table->find($asignatura_id)->nombre;
        $data['page_title'] = 'Añadiendo carga global';
        $data['options'] = $options;
        $this->load->view('cargaglobal/add', $data);
    }

    public function create_carga(){
        $global = new CargaGlobal;
        $q = Doctrine_Query::create()->select('c.id')->from('Curso c')->orderBy('c.id desc')->limit(1);
        $global->curso_id = $q->fetchOne();
        $global->fromArray($this->input->post());
        if(!$global->isValid()){
            $this->alerts = $global->getErrorStackAsString();
            $this->add_carga($this->input->post('asignatura_id'));
            
        }else{
            $global->save();
            $this->notices = 'Asignatura añadida correctamente';
            $this->session->set_flashdata('notice', $this->notices);
            redirect('titulaciones/index');
        }
    }
}

/* Fin archivo asignaturas.php */