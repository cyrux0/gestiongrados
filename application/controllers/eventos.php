<?php

class Eventos extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->eventos_table = Doctrine::getTable('Evento');
    }
        
    public function index(){
        //Mostrar un calendario completo ¿?
        echo "Construyéndose";
    }
    
    public function add(){
        //TO-DO Permitir añadir un evento
        $evento = new Evento();
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
        redirect('titulaciones/index');
    }    
   
    public function update($id){
        //TO-DO
    }
    
    public function delete(){
        //TO-DO
    }
}
