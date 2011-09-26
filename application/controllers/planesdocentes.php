<?php

class PlanesDocentes extends MY_Controller{
  function __construct(){
    parent::__construct();
    $this->globales_table = Doctrine::getTable('PlanDocente');
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
    $this->cursos_table = Doctrine::getTable('Curso');
    $this->layout = '';
    $this->alerts = '';
    $this->notices = '';
    $this->_filter(array('create', 'edit', 'update', 'delete', 'load'), array($this, 'authenticate'), 2); // Sólo al planner 
  }

    public function create(){
        $global = new PlanDocente;
        $q = Doctrine_Query::create()->select('c.id')->from('Curso c')->orderBy('c.id desc')->limit(1);
        $global->curso_id = $q->fetchOne();
        $global->fromArray($this->input->post());
        if($this->_submit_validate()==FALSE){
            $this->add_carga($this->input->post('asignatura_id'));
        }else{
            $global->save();
            $this->notices = 'Asignatura añadida correctamente';
            $this->session->set_flashdata('notice', $this->notices);
            redirect('titulaciones/index');
        }
    }
    
    public function edit($id){
        $global = $this->globales_table->find($id);
        $action = '/planesdocentes/update/' . $id;
        $data['data'] = array('result' => $global, 'action' => $action);
        $data['nombre_asignatura'] = $this->asignaturas_table->find($global->asignatura_id)->nombre;
        $data['page_title'] = 'Editando carga global';
        $this->load->view('PlanDocente/edit', $data);
    }

    public function update($id){
        $global = $this->globales_table->find($id);
        $global->fromArray($this->input->post());
        if($this->_submit_validate()==FALSE){
            $this->edit($id);
        }else{
            $global->save();
            $this->notices = 'Carga actualizada correctamente';
            $this->session->set_flashdata('notices', $this->notices);
            redirect('titulaciones/show/' . $global->Asignatura->titulacion_id);
        }
    }

    public function load($asignatura_id, $curso_id){
        $q = Doctrine_Query::create()->select('p.*')->from('PlanDocente p')->where('p.curso_id = ' . $curso_id . ' AND p.asignatura_id = ' . $asignatura_id)->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $result = $q->execute();
        $global = new PlanDocente;
        $global->fromArray($result[0]);
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
        $this->form_validation->set_rules('horas_teoria', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_teoria', 'trim|is_natural');
        $this->form_validation->set_rules('horas_problemas', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_problemas', 'trim|is_natural');
        $this->form_validation->set_rules('horas_informatica', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_informatica', 'trim|is_natural');
        $this->form_validation->set_rules('horas_lab', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_lab', 'trim|is_natural');
        $this->form_validation->set_rules('horas_campo', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_campo', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_teoria', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_problemas', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_informatica', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_lab', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_campo', 'trim|is_natural');
        
        return $this->form_validation->run();
    }
}

/* Fin del archivo planesdocentes.php */
