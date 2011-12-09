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
        $this->page_title = "Gestión de asignaturas";
        $this->modelObject = null;
        $this->_filter(array('add_to', 'create', 'edit', 'update', 'delete'), array($this, 'authenticate'), 1); // Sólo permitimos a un usuario de tipo planificador (1)
        $this->_filter(array('add_carga', 'show', 'add_carga_from_file'), array($this, 'authenticate'), 1); // Sólo se lo permitimos al usuario de tipo planificador (2)
    }

    public function add_to($id) {
        $titulacion = $this->titulaciones_table->find($id);
        $data['nombre_titulacion'] =  $titulacion->nombre;
        $asignatura = new Asignatura;
        $asignatura -> titulacion_id = $id;
        $action = 'asignaturas/create/' . $asignatura -> id;
        $data['data'] = array('result' => $asignatura, 'action' => $action, 'cursos' => array_combine(range(1,$titulacion->num_cursos), range(1,$titulacion->num_cursos)));
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

    public function show($id, $id_curso = ''){
        if(!$id_curso) redirect('cursos/select_curso/asignaturas/show/' . $id); //Arreglar esto, ya que select_curso solo coge los dos primeros parámetros, debería coger todos.
        $asignatura = $this->asignaturas_table->find($id);
        $q = Doctrine_Query::create()->select('c.*, p.*, a.descripcion')->from('PlanActividad p')->innerJoin('p.plandocente c')->innerJoin('p.actividad a')->where('c.id_curso = ? AND c.id_asignatura = ?', array($id_curso, $id));
        $resultado = $q->execute();
        if($this->input->post('js'))
            unset($this->layout);
        
        if(count($resultado))
        {
            $this->load->view('asignaturas/show', array('cargas' => $resultado, 'asignatura' => $asignatura));
        }
        else 
        {
            $this->load->view('asignaturas/sin_plan_docente', array('id_asignatura' => $id, 'id_curso' => $id_curso));
        }
        
    }
    
    public function edit($id) {
        $asignatura = $this -> asignaturas_table -> find($id);
        $action = 'asignaturas/update/' . $asignatura -> id;
        $data['data'] = array('result' => $asignatura, 'action' => $action, 'cursos' => range(1, $asignatura->Titulacion->num_cursos));
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
    
    public function importar()
    {
        $data = array('action' => 'asignaturas/subir_archivo');
        $this->load->view('PlanDocente/from_file', $data);
    }
    
    public function subir_archivo()
    {
              $this->layout ='';
        $config['upload_path'] = './application/upload/data/';
        $config['allowed_types'] = '*';
        $config['max_size']	= '100';

        $this->load->library('upload', $config);
        if(!$this->upload->do_upload()){
            $error = array('error' => $this->upload->display_errors(), 'action' => 'asignaturas/subir_archivo');
            $this->load->view('PlanDocente/from_file', $error);
        }else{
            $this->load->database();
            $data = $this->upload->data();
            $query_array = explode(";", file_get_contents($data['full_path']));
            Doctrine_Manager::connection()->execute('SET FOREIGN_KEY_CHECKS = 0');
            Doctrine::loadData($data['full_path'], true);
            unlink($data['full_path']);
        }
    }
    
    public function exportar()
    {
        Doctrine::dumpData("./application/downloads/data/", true);
        $this->load->helper('download');
        $data = file_get_contents("application/downloads/data/Asignatura.yml");
        force_download("asignaturas.yml", $data);
    }
    
    private function _submit_validate(){
        $this->form_validation->set_rules('codigo', 'Código', 'required|exact_length[3]|numeric');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]|max_length[200]|alpha_ext'); // Hay que crear la regla alpha_numeric_ext igual que la alpha_ext pero con números.
        $this->form_validation->set_rules('abreviatura', 'Abreviatura', 'required|min_length[1]|max_length[5]|alpha_ext');
        $this->form_validation->set_rules('creditos', 'Créditos', 'required|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('materia', 'Materia', 'required|min_length[3]|max_length[100]|alpha_ext');
        $this->form_validation->set_rules('departamento', 'Departamento', 'required|min_length[3]|max_length[200]|alpha_ext');
        $this->form_validation->set_rules('curso', 'Curso', 'required|is_natural_no_zero');
        
        return $this->form_validation->run() && $this->modelObject->isValid();
    }
}

/* Fin archivo asignaturas.php */