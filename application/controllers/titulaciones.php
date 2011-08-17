<?php
class Titulaciones extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this -> titulaciones_table = Doctrine::getTable('Titulacion');
        $this -> asignaturas_table = Doctrine::getTable('Asignatura');
        $this -> layout = '';
        $this -> load -> library('form_validation');
        $this->load->library('session');
        $this->form_validation->set_rules('codigo', 'Código', 'required|trim|xss_clean|is_natural|exact_length[4]|unique[titulacion.codigo]');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|alpha|min_length[5]|max_length[200]|unique[titulacion.nombre]');
        $this->form_validation->set_rules('creditos', 'Créditos', 'required|trim|xss_clean|is_natural');
        $this->form_validation->set_rules('num_cursos', 'Número de cursos', 'required|trim|xss_clean|is_natural_no_zero');
    }

    public function index() {

        $titulaciones = $this -> titulaciones_table -> findAll();

        //Conseguimos los items mediante el modelo
        $data['titulaciones'] = $titulaciones;
        $data['page_title'] = 'INDEX TITULACIONES';

        if($this -> input -> post('js') == '1') {
            unset($this -> layout);
            $this -> load -> view('titulaciones/_titulaciones', $data);
        } else {
            //Mostramos
            $this -> load -> view('titulaciones/index', $data);
        }
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
        if($this -> form_validation -> run() == FALSE) {
            if($this -> input -> post('remote') == "true") {
                unset($this->layout);
                $alert = '';
                $this->output->set_content_type('application/json');
                $response['messages'] = $this->load->view('layouts/notice_and_alerts', array('alert' => $alert), TRUE);
                $response['success'] = 0;
                echo json_encode($response);
                //$this->output->set_output(json_encode($response));                
            }else
                $this->add();
            //$this -> load -> view('titulaciones/add', array('data' => array('action' => 'titulaciones/create', 'titulacion' => $titulacion), 'page_title' => 'ADD TITULACIONES'));
        } else {

            $titulacion -> fromArray($this -> input -> post());
            $titulacion -> save();
            $notice = 'Titulación añadida correctamente';
            if($this -> input -> post('remote') == "true") {
                unset($this -> layout);
                $this->output->set_content_type('application/json');
                $response['success'] = 1;
                $response['view'] = $this -> load -> view('titulaciones/_titulacion', array('item' => $titulacion), TRUE);
                $response['messages'] = $this->load->view('layouts/notice_and_alerts', array('notice' => $notice), TRUE);
                // echo json_encode($response);
                $this->output->set_output(json_encode($response));
            } else {
                $this->session->set_flashdata('notice', $notice);
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
        if($this->form_validation->run() == FALSE){
            $this->edit($id);
        }else{
            $titulacion -> save();
            redirect('titulaciones/index');
        }
    }

    public function show($id) {
        $data['asignaturas'] = $this -> asignaturas_table -> findByTitulacion_id($id);
        $data['titulacion'] = $this -> titulaciones_table -> find($id);
        $data['page_title'] = 'INDEX ASIGNATURAS';
        $this -> load -> view('titulaciones/show', $data);
    }
    
}

/* Fin del archivo titulaciones.php */
