<?php
class Titulaciones extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->titulaciones_table = Doctrine::getTable('Titulacion');
        $this->asignaturas_table = Doctrine::getTable('Asignatura');
        $this->layout = '';
        $this->notices = '';
        $this->alerts = '';
        $this->_filter(array('add', 'create', 'delete', 'edit', 'update'), array($this, 'authenticate'), 1); // Sólo admin
    }

    public function index() {

        $titulaciones = $this -> titulaciones_table -> findAll();

        //Conseguimos los items mediante el modelo
        $data['titulaciones'] = $titulaciones;
        $data['page_title'] = 'INDEX TITULACIONES';
        $data['enlace'] = 'titulaciones/show/';
        if($this -> input -> post('js') == '1') {
            unset($this -> layout);
            $this -> load -> view('titulaciones/_titulaciones', $data);
        } else {
            //Mostramos
            $this -> load -> view('titulaciones/index', $data);
        }
    }

    public function show_informes($id_curso, $id_titulacion)
    {
        if(!isset($id_curso)) redirect('cursos/select_curso/titulaciones/show_informes/');
        if(!isset($id_titulacion)) redirect('titulaciones/select_titulacion/titulaciones/show_informes/'. $id_curso);
        
        $data['asignaturas'] = $this->asignaturas_table->findByTitulacion_id($id_titulacion);
        $data['id_curso'] = $id_curso;
        $this -> load -> view('titulaciones/show_informes', $data);
    }
    
    
    public function add() {
        //Mostramos vista
        $titulacion = new Titulacion();
        if($this -> input -> post('js')) {
            unset($this -> layout);
        }
        $action = 'titulaciones/create';
        $this -> load -> view('titulaciones/add', array('data' => array('titulacion' => $titulacion, 'action' => $action), 'page_title' => 'ADD TITULACIONES'));
    }

    public function create() {
        $titulacion = new Titulacion;
        $titulacion -> fromArray($this -> input -> post());

        if($this->_submit_validate()==FALSE){
            if($this->input->post('remote') == "true"){
                unset($this->layout);
                $this->output->set_content_type('application/json');
                $response['messages'] = $this->load->view('layouts/notice_and_alerts', null, TRUE);
                $response['success'] = 0;
                echo json_encode($response);
            }else{
                $this->add();
            }
        }else{
            $titulacion -> save();
            $pretags = '<li><span>';
            $posttags = '</span><span>' . anchor('asignaturas/add_to/' . $titulacion->id, '+') . '</span>' . anchor('titulaciones/delete/' . $titulacion->id, 'X') . '</li>';
            $this->notices = 'Titulación añadida correctamente';
            if($this -> input -> post('remote') == "true") {
                unset($this -> layout);
                $this->output->set_content_type('application/json');
                $response['success'] = 1;
                $response['view'] = $this->load->view('titulaciones/_titulacion', array('item' => $titulacion, 'pretags' => $pretags, 'posttags' => $posttags, 'enlace' => 'titulaciones/show/'), TRUE);
                $response['messages'] = $this->load->view('layouts/notice_and_alerts', null, TRUE);
                $this->output->set_output(json_encode($response));
            } else {
                $this->session->set_flashdata('notices', $notice);
                redirect('titulaciones/index');
            }
        }
    }

    public function delete($id) {
        $titulacion = $this -> titulaciones_table -> find($id);
        $titulacion -> asignaturas -> delete();
        $titulacion -> delete();
        redirect('titulaciones/index');
    }

    public function edit($id) {
        $titulacion = $this -> titulaciones_table -> find($id);
        $action = 'titulaciones/update/' . $id;
        $data['data'] = array('titulacion' => $titulacion, 'action' => $action);
        $data['page_title'] = 'EDIT TITULACIONES';
        $this -> load -> view('titulaciones/edit', $data);
    }

    public function update($id) {
        $titulacion = $this -> titulaciones_table -> find($id);
        $titulacion -> fromArray($this -> input -> post());
        if($this->_submit_validate()==FALSE){
        	$this->alerts = $titulacion->getErrorStackAsString();
            $this->edit($id);
        }else{
            $titulacion -> save();
			$notice = 'Titulación actualizada correctamente';
			$this->session->set_flashdata('notices', $notice);
            redirect('titulaciones/index');
        }
    }

    public function show($id, $id_curso = '') {
        if(!$id_curso) redirect('cursos/select_curso/titulaciones/show/' . $id);
        $data['asignaturas'] = $this->asignaturas_table->findByTitulacion_id($id);
        $data['titulacion'] = $this->titulaciones_table->find($id);
        $data['page_title'] = 'INDEX ASIGNATURAS';
        $data['id_curso'] = $id_curso;
        if($this->input->post('js')){
            unset($this->layout);
        }
        $this -> load -> view('titulaciones/show', $data);
    }
    
    public function show_planificacion($id_curso, $id_titulacion)
    {
        if(!isset($id_curso)) redirect('cursos/select_curso/titulaciones/show_planificacion/');
        if(!isset($id_titulacion)) redirect('titulaciones/select_titulacion/titulaciones/show_planificacion/' . $id_curso);
        
        $titulacion = Doctrine::getTable('Titulacion')->find($id_titulacion);
        $salida_total = $titulacion->getPlanificacion($id_curso);
        
        $this->load->view('titulaciones/show_planificacion', array('salida' => $salida_total));
    }
    
    public function exportar_planificacion($id_curso, $id_titulacion)
    {
        if(!isset($id_curso)) redirect('cursos/select_curso/titulaciones/exportar_planificacion/');
        if(!isset($id_titulacion)) redirect('titulaciones/select_titulacion/titulaciones/exportar_planificacion/' . $id_curso);
        
        $titulacion = Doctrine::getTable('Titulacion')->find($id_titulacion);
        $salida_total = $titulacion->getPlanificacion($id_curso);
        $headers = array('Asignatura', 
            'Horas teoría', 'Grupos Teoría', 'Horas semanales teoría',
            'Horas laboratorio', 'Grupos laboratorio', 'Horas semanales laboratorio',
            'Horas problemas', 'Grupos problemas', 'Horas semanales problemas',
            'Horas informática', 'Grupos informática', 'Horas semanales informática',
            'Horas prácticas de campo', 'Grupos prácticas de campo', 'Horas semanales prácticas de campo',
            );

        $salida_final = array();
        foreach($salida_total as $linea)
        {
            $linea_array[0] = $linea[0];
            for($i = 1; $i<5 ; $i++)
            {
                $linea_array[1+3*($i-1)] = isset($linea[$i])? $linea[$i][0] : '';
                $linea_array[2+3*($i-1)] = isset($linea[$i])? $linea[$i][1] : '';
                $linea_array[3+3*($i-1)] = isset($linea[$i])? $linea[$i][2] : '';
            }
            $salida_final[] = $linea_array;
        }
        array_unshift($salida_final, $headers);
        $this->load->helper('importacion_csv_helper');
        exportador_csv('./application/downloads/temp.csv', $salida_final);
        $data = file_get_contents('./application/downloads/temp.csv');
        $name = 'planificacion.csv';
        $this->load->helper('download');
        force_download($name, $data);
    }
    public function index_cargas($id_curso){
        if(!$id_curso) redirect('cursos/select_curso/titulaciones/index_cargas');
        $titulaciones = $this -> titulaciones_table -> findAll();
        //Conseguimos los items mediante el modelo
        $data['titulaciones'] = $titulaciones;
        $data['page_title'] = 'Planificación docente';
        $data['id_curso'] = $id_curso;
        $this->load->view('titulaciones/index_cargas', $data);
    }
    
    private function _submit_validate(){
        $this->form_validation->set_rules('codigo', 'Código', 'required|trim|xss_clean|is_natural|exact_length[4]|unique[titulacion.codigo]');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|alpha_ext|min_length[5]|max_length[200]|unique[titulacion.nombre]');
        $this->form_validation->set_rules('creditos', 'Créditos', 'required|trim|xss_clean|is_natural');
        $this->form_validation->set_rules('num_cursos', 'Número de cursos', 'required|trim|xss_clean|is_natural_no_zero');
        
        return $this->form_validation->run(); 
    }

    public function select_titulacion(){
        list($controller, $action, $route) = explode('/', $this->uri->uri_string(), 3);
        $titulaciones = $this->titulaciones_table->findAll();
        $this->load->view('titulaciones/index', array('titulaciones' => $titulaciones, 'action' => $route));
    }
}

/* Fin del archivo titulaciones.php */
