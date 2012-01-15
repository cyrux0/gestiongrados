<?php
class Titulaciones extends MY_Controller {

    /**
     * Constructor del controlador titulaciones. Gestiona los permisos e inicializa algunos parámetros
     * 
     */
    function __construct() {
        parent::__construct();
        $this->titulaciones_table = Doctrine::getTable('Titulacion');
        $this->asignaturas_table = Doctrine::getTable('Asignatura');
        $this->layout = '';
        $this->notices = '';
        $this->alerts = '';
        $this->_filter(array('add', 'create', 'delete', 'edit', 'update', 'show_informes', 'show', 'exportar_planificacion'), array($this, 'authenticate'), 1); // Sólo admin
    }

    /**
     * Acción index para titulaciones, genera un listado de las titulaciones del sistema.
     * 
     */
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

    /**
     * Muestra un listado de las asignaturas disponibles de la titulación para mostrar sus informes
     * 
     * @param integer $id_curso Curso del que se quiere mostrar los informes.
     * @param integer $id_titulacion Titulación de la que se quiere mostrar el listado de informes.
     */
    public function show_informes($id_curso, $id_titulacion)
    {
        if(!isset($id_curso)) redirect('cursos/select_curso/titulaciones/show_informes/');
        if(!isset($id_titulacion)) redirect('titulaciones/select_titulacion/titulaciones/show_informes/'. $id_curso);
        
        $data['asignaturas'] = $this->asignaturas_table->findByTitulacion_id($id_titulacion);
        $data['id_curso'] = $id_curso;
        $this -> load -> view('titulaciones/show_informes', $data);
    }
    
    /**
     * Muestra el formulario para añadir una titulación.
     */
    public function add() {
        //Mostramos vista
        $titulacion = new Titulacion();
        if($this -> input -> post('js')) {
            unset($this -> layout);
        }
        $action = 'titulaciones/create';
        $this -> load -> view('titulaciones/add', array('data' => array('titulacion' => $titulacion, 'action' => $action), 'page_title' => 'ADD TITULACIONES'));
    }

    /**
     * Recibe los parámetros del formulario de creación y crea la titulación en la base de datos.
     */
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
            $this->notices = 'Titulación añadida correctamente';
            if($this -> input -> post('remote') == "true") {
                unset($this -> layout);
                $this->output->set_content_type('application/json');
                $response['success'] = 1;
                $response['view'] = $this->load->view('titulaciones/_titulacion', array('item' => $titulacion), TRUE);
                $response['messages'] = $this->load->view('layouts/notice_and_alerts', null, TRUE);
                $this->output->set_output(json_encode($response));
            } else {
                $this->session->set_flashdata('notices', $notice);
                redirect('titulaciones/index');
            }
        }
    }
    
    /**
     * Borra una titulación del sistema
     * @param integer $id Identificador de la titulación a borrar.
     */
    public function delete($id) {
        $titulacion = $this -> titulaciones_table -> find($id);
        if(!$titulacion) show_404();
        $titulacion -> asignaturas -> delete();
        $titulacion -> delete();
        redirect('titulaciones/index');
    }

    /**
     * Muestra un formulario para editar la titulación.
     * @param integer $id Identificador de la titulación a editar.
     */
    public function edit($id) {
        $titulacion = $this -> titulaciones_table -> find($id);
        if(!$titulacion) show_404();
        $action = 'titulaciones/update/' . $id;
        $data['data'] = array('titulacion' => $titulacion, 'action' => $action);
        $data['page_title'] = 'EDIT TITULACIONES';
        $this -> load -> view('titulaciones/edit', $data);
    }

    /**
     * Recibe los datos del formulario de edición y actualiza la titulación.
     * @param integer $id Identificador de la titulación a borrar
     */
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

    /**
     * Muestra las asignaturas de una titulación.
     * @param integer $id Identificador de la titulación.
     * @param integer $id_curso Identificador del curso.
     */
    public function show($id, $id_curso) {
        if(!isset($id_curso)) redirect('cursos/select_curso/titulaciones/show/' . $id);
        $asignaturas = $this->asignaturas_table->findByTitulacion_id($id);
        $data['titulacion'] = $this->titulaciones_table->find($id);
        
        $asignaturas_array = array();
        foreach($asignaturas as &$asignatura)
        {
            $plan = Doctrine_Query::create()
                    ->select('p.id')
                    ->from('PlanDocente p')
                    ->where('id_curso = ?', $id_curso)
                    ->andWhere('id_asignatura = ?', $asignatura->id)
                    ->execute();
         
            $asignatura = $asignatura->toArray();
            $asignatura['id_plandocente'] = $plan->count()? $plan[0]->id: 0;
            $asignaturas_array[] = $asignatura;
        }
        $data['asignaturas'] = $asignaturas_array;
        if(!$data['titulacion']) show_404();
        $data['page_title'] = 'INDEX ASIGNATURAS';
        $data['id_curso'] = $id_curso;
        if($this->input->post('js')){
            unset($this->layout);
        }
        $this -> load -> view('titulaciones/show', $data);
    }
    
    /**
     * Muestra la planificación docente completa de una titulación con una tabla con todas las asignaturas.
     * @param integer $id_curso Identificador del curso.
     * @param integer $id_titulacion Identificador de la titulación.
     */
    public function show_planificacion($id_curso, $id_titulacion)
    {
        if(!isset($id_curso)) redirect('cursos/select_curso/titulaciones/show_planificacion/');
        if(!isset($id_titulacion)) redirect('titulaciones/select_titulacion/titulaciones/show_planificacion/' . $id_curso);
        
        $titulacion = Doctrine::getTable('Titulacion')->find($id_titulacion);
        if(!$titulacion) show_404();
        $salida_total = $titulacion->getPlanificacion($id_curso);
        
        $this->load->view('titulaciones/show_planificacion', array('salida' => $salida_total, 'id_curso' => $id_curso, 'id_titulacion' => $id_titulacion));
    }
    
    /**
     * Exporta a CSV la planificación docente de una titulación.
     * @param integer $id_curso Identificador del curso.
     * @param integer $id_titulacion Identificador de la titulación.
     */
    public function exportar_planificacion($id_curso, $id_titulacion)
    {
        if(!isset($id_curso)) redirect('cursos/select_curso/titulaciones/exportar_planificacion/');
        if(!isset($id_titulacion)) redirect('titulaciones/select_titulacion/titulaciones/exportar_planificacion/' . $id_curso);
        
        $titulacion = Doctrine::getTable('Titulacion')->find($id_titulacion);
        if(!$titulacion) show_404();
        // Obtenemos la planificación de la titulación
        $salida_total = $titulacion->getPlanificacion($id_curso);
        $headers = array('Asignatura', 
            'Horas teoría', 'Grupos Teoría', 'Horas semanales teoría',
            'Horas laboratorio', 'Grupos laboratorio', 'Horas semanales laboratorio',
            'Horas problemas', 'Grupos problemas', 'Horas semanales problemas',
            'Horas informática', 'Grupos informática', 'Horas semanales informática',
            'Horas prácticas de campo', 'Grupos prácticas de campo', 'Horas semanales prácticas de campo',
            );

        array_unshift($salida_final, $headers);
        
        $this->load->helper('importacion_csv_helper');
        exportador_csv('./application/downloads/temp.csv', $salida_final);
        $data = file_get_contents('./application/downloads/temp.csv');
        $name = 'planificacion.csv';
        $this->load->helper('download');
        force_download($name, $data);
    }
    
    /**
     * Muestra un listado de las asignaturas con un enlace a la planificación docente.
     * @param type $id_curso Identificador del curso.
     */
    public function index_cargas($id_curso){
        if(!$id_curso) redirect('cursos/select_curso/titulaciones/index_cargas');
        $titulaciones = $this -> titulaciones_table -> findAll();
        //Conseguimos los items mediante el modelo
        $data['titulaciones'] = $titulaciones;
        $data['page_title'] = 'Planificación docente';
        $data['id_curso'] = $id_curso;
        $this->load->view('titulaciones/index', $data);
    }
    
    private function _submit_validate(){
        $this->form_validation->set_rules('codigo', 'Código', 'required|trim|xss_clean|is_natural|exact_length[4]|unique[titulacion.codigo]');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|alpha_ext|min_length[5]|max_length[200]|unique[titulacion.nombre]');
        $this->form_validation->set_rules('creditos', 'Créditos', 'required|trim|xss_clean|is_natural');
        $this->form_validation->set_rules('num_cursos', 'Número de cursos', 'required|trim|xss_clean|is_natural_no_zero');
        
        return $this->form_validation->run(); 
    }

    /**
     * Acción para redireccionar y seleccionar una titulación en otro controlador en el que haga falta esta selección.
     * Muestra un listado de las titulaciones para elegir una.
     */
    public function select_titulacion(){
        list($controller, $action, $route) = explode('/', $this->uri->uri_string(), 3);
        $titulaciones = $this->titulaciones_table->findAll();
        $flash = $this->session->flashdata('alerts');
        
        $this->load->view('titulaciones/select_titulacion', array('titulaciones' => $titulaciones, 'action' => $route));
    }
}

/* Fin del archivo titulaciones.php */
