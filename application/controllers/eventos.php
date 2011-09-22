<?php

class Eventos extends MY_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->eventos_table = Doctrine::getTable('Evento');
        $this->alerts = '';
        $this->notices = '';
    }
        
    public function index($curso_id){
        //Obtener todas las fechas, ordenar por fecha inicial y mostrarlas en una lista, con un botón para borrarlas.
        $q = Doctrine_Query::create()->select('e.*')->from('Evento e')->where('e.curso_id = ' . $curso_id)->orderBy('e.fecha_inicial');
        $eventos = $q->execute();
        $calendar_events = array(); 
        $this->load->view('eventos/index', array('eventos' => $eventos, 'curso_id' => $curso_id));
    }
    
    public function add($curso_id){
        //TO-DO Permitir añadir un evento
        $evento = new Evento();
        $evento->curso_id = $curso_id;
        $options = array_combine($evento->tipo_evento_values, array('Festivo', 'Vacaciones', 'Evento especial laborable'));
        $action = 'eventos/create';
        $this->load->view('eventos/add', array('page_title' => 'Nuevo evento', 'data' => array('evento'=> $evento, 'action' => $action, 'options' => $options)));
    }
    
    public function create(){
        $evento = new Evento();
        if(isset($_POST['fecha_individual'])){
            $_POST['fecha_individual'] = 1;
        }else{
            $_POST['fecha_individual'] = 0;
        }
        $evento->fromArray($this->input->post());
        if(!$evento->isValid()){
            $this->alerts = $evento->getErrorStackAsString();
            $this->add($this->input->post('curso_id'));            
        }else{
            $evento->save();
            $this->notices = 'Evento añadido correctamente';
            $this->session->set_flashdata('notices', $this->notices);
            redirect('eventos/index/' . $evento->curso_id);
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
    
    private function _submit_validate(){
        $this->form_validation->set_rule('nombre_evento', 'trim|required|alpha_ext|min_length[5]|max_length[255]');
        $this->form_validation->set_rule('fecha_inicial', 'trim|required');
        if($this->input->post('fecha_individual')){
            $this->form_validation->set_rule('fecha_final', 'trim|required');
        }
        return $this->form_validation->run();
    }
}
