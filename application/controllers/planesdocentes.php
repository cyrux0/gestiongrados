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
        $global->id_curso = $this->input->post('id_curso');
        $global->id_asignatura = $this->input->post('id_asignatura');
        foreach($this->input->post('horas') as $key => $horas){
            $array_grupos = $this->input->post('grupos');
            $array_horas_semanales = $this->input->post('horas_semanales');
            $array_alternas = $this->input->post('alternas');              
            if($horas){
                $planactividad = new PlanActividad;
                $planactividad->horas = $horas;
                $planactividad->grupos = $array_grupos[$key];
                $planactividad->horas_semanales = $array_horas_semanales[$key];
                $planactividad->alternas = isset($array_alternas[$key])? 1:0;
                $planactividad->id_actividad = $key;             
                $global->planactividades[] = $planactividad;
            }
        }
        
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

    public function upload_file(){
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv|xls';
		$config['max_size']	= '100';
        
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload()){
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('PlanDocente/from_file', $error);
        }else{
            $data = $this->upload->data();
            try{
                $this->_parse_data($data['full_path']);
                $rows = $this->_parse_data($data['full_path'], true);
                $this->load->view('plandocente/upload_success', array('rows' => $rows));
            }catch(Exception $e){
                $error = array('error' => $e->getMessage());
                $this->load->view('PlanDocente/from_file', $error);
            }
        }
    }
    
    public function make_upload(){
        $this->load->view('PlanDocente/from_file', array('error' => ''));
    }
    private function _parse_data($filename, $process = false){
        $file = fopen($filename, 'rb');
        $data = fgetcsv($file, 0, ',');
        if(!$data){
            throw new Exception("Error en la línea 1: Los campos son incorrectos");
            return false;
        }
        $fields = array();
        foreach($data as $field)
            $fields[] = trim($field);
        $row = 2;

        // fields = asignatura | actividad | horas(totales) | grupos | horas_semanales | alternas | id_curso
        while(($data = fgetcsv($file, 0, ',')) != false){
            if(count($fields) != count($data)){
                throw new Exception("Error en la línea $row: número incorrecto de valores");
                return false;
            }
            $planactividad = new PlanActividad;
            $datarow = array_combine($fields, $data);
            if(!isset($datarow['id_asignatura'])){
                throw new Exception("Error en la línea $row: falta el identificador de la asignatura");
            }else{
                $plandocente = Doctrine_Query::create()
                                ->select("p.*")
                                ->from('PlanDocente p')
                                ->where('id_asignatura = ? AND id_curso = ?', array($datarow['id_asignatura'], $datarow['id_curso']))
                                ->execute();
                if(!$plandocente->count()){
                    $plandocente = new PlanDocente();
                    $plandocente->id_asignatura = $datarow['id_asignatura'];
                    $plandocente->id_curso = $datarow['id_curso'];
                    if(!$plandocente->isValid()){
                        
                        throw new Exception("Error en la línea $row | " . $plandocente->getErrorStackAsString());
                    }else{    
                        $plandocente->save();    
                    }
                }else{
                    $plandocente = $plandocente->getFirst();
                }
                $planactividad->id_plandocente = $plandocente->id;
            }
            $planactividad->fromArray($datarow);
            if(!$planactividad->isValid()){
                throw new Exception("Error en la línea $row | " . $planactividad->getErrorStackAsString());
            }else{           
                if($process){
                    $planactividad->save();
                }
            }
            $row ++;
        }
        return $row;
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
