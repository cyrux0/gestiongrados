<?php

class Eventos extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->eventos_table = Doctrine::getTable('Evento');
    }
        
    public function index($curso_id){
        //Obtener todas las fechas, ordenar por fecha inicial y mostrarlas en una lista, con un botón para borrarlas.
        $q = Doctrine_Query::create()->select('e.*')->from('Evento e')->where('e.curso_id = ' . $curso_id)->orderBy('e.fecha_inicial');
        $eventos = $q->execute();
        $this->load->view('eventos/index', array('eventos' => $eventos->toArray(), 'curso_id' => $curso_id));
    }
    
    public function add($curso_id){
        //TO-DO Permitir añadir un evento
        $evento = new Evento();
        $evento->curso_id = $curso_id;
        $options = array_combine($evento->tipo_evento_values, array('Examen', 'Festivo', 'Vacaciones', 'Fecha Especial'));
        $action = 'eventos/create';
        $this->load->view('eventos/add', array('page_title' => 'Nuevo evento', 'data' => array('evento'=> $evento, 'action' => $action, 'options' => $options)));
    }
    
    public function create(){
        $evento = new Evento();
        $evento->fromArray($this->input->post());
        list($day1, $month1, $year1) = explode('/', $this->input->post('fecha_inicial'));
        list($day2, $month2, $year2) = explode('/', $this->input->post('fecha_final'));
        $evento->fecha_inicial = $year1 . '-' . $month1 . '-' . $day1;
        $evento->fecha_final = $year2 . '-' . $month2 . '-' . $day2;
        $evento->save();
        redirect('eventos/index/' . $evento->curso_id);
    }    
   
    public function delete($id){
        $evento = $this->eventos_table->find($id);
        $curso_id = $evento->curso_id;
        $evento->delete();
        redirect('eventos/index' . $curso_id);

    }
}
