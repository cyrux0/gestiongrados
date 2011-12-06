<?php

class Eventos extends MY_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->eventos_table = Doctrine::getTable('Evento');
        $this->alerts = '';
        $this->notices = '';
		$this->modelObject = null;
        $this->_filter(array('add', 'create', 'delete', 'index', 'export_calendar'), array($this, 'authenticate'), 1); // Sólo al planner
    }
        
    public function index($id_curso){
        if(!$id_curso) redirect('cursos/select_curso/eventos/index');
        //Obtener todas las fechas, ordenar por fecha inicial y mostrarlas en una lista, con un botón para borrarlas.
        $q = Doctrine_Query::create()->select('e.*')->from('Evento e')->where('e.curso_id = ' . $id_curso)->orderBy('e.fecha_inicial');
        $eventos = $q->execute();
        $calendar_events = array(); 
        $this->load->view('eventos/index', array('eventos' => $eventos, 'id_curso' => $id_curso));
    }
    
    public function add($id_curso){
        if(!$id_curso) redirect('cursos/select_curso/eventos/add');
        //TO-DO Permitir añadir un evento
        $evento = new Evento();
        $evento->curso_id = $id_curso;
        $options = array_combine($evento->tipo_evento_values, array('Festivo', 'Vacaciones', 'Evento especial laborable'));
        $action = 'eventos/create';
        $this->load->view('eventos/add', array('page_title' => 'Nuevo evento', 'data' => array('evento'=> $evento, 'action' => $action, 'options' => $options)));
    }
    
    public function create(){
        $this->modelObject = new Evento();
        if(isset($_POST['fecha_individual'])){
            $_POST['fecha_individual'] = 1;
        }else{
            $_POST['fecha_individual'] = 0;
        }
        $this->modelObject->fromArray($this->input->post());

        if($this->_submit_validate() == FALSE){
            if($this->input->post('remote')){
                unset($this->layout);
                $data['errors'] = validation_errors('<p class="alert">', '</p>');
                echo json_encode($data);
            }else{
                $this->add($this->input->post('curso_id'));
            }            
        }else{
            $this->modelObject->save();
            if($this->input->post('remote')){
                unset($this->layout);
                $array_event = $this->modelObject->toArray();
                $array_event['success'] = "true";
                echo json_encode($array_event);
            }else{
                $this->notices = 'Evento añadido correctamente';
                $this->session->set_flashdata('notices', $this->notices);
                redirect('eventos/index/' . $this->modelObject->curso_id);
            }
        }
    }    
   
    public function delete($id){
        $evento = $this->eventos_table->find($id);
        $curso_id = $evento->curso_id;
        $evento->delete();
        redirect('eventos/index/' . $curso_id);

    }
    
    public function fetch_events($curso_id){
        unset($this->layout);
        $q = Doctrine_Query::create()->select('e.*')->from('Evento e')->where('e.curso_id = ' . $curso_id)->orderBy('e.fecha_inicial');
        $eventos = $q->execute();
        $calendar_events = array(); 
        foreach($eventos->toArray() as $evento){
            $event = array();
            $evento = (Object) $evento;
            $event['id'] = $evento->id;
            $event['title'] = $evento->nombre_evento;
            $event['start'] = $evento->fecha_inicial;
            $event['end'] = $evento->fecha_final;
            $calendar_events[] = $event;
        }
        $eventos_json = json_encode($calendar_events);
        echo $eventos_json;
    }
    
    public function export_calendar($id_curso)
    {
        $this->load->helper('importacion_csv_helper');
        export_calendar_csv($id_curso);
        $data = file_get_contents('./application/downloads/temp.csv');
        $name = 'calendar.csv';
        $this->load->helper('download');
        force_download($name, $data);
    }
    
    private function _submit_validate(){
        $this->form_validation->set_rules('nombre_evento', 'Nombre del evento', 'trim|required|alpha_ext|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('fecha_inicial', 'Fecha de inicio del evento', 'trim|required|callback__doctrine_validation[fecha_inicial]');
        if($this->input->post('fecha_individual')){
            $this->form_validation->set_rules('fecha_final', 'Fecha de finalización del evento', 'trim|required');
        }
        return $this->form_validation->run();
    }
    
    
    
    
}
