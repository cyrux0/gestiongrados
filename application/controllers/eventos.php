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
    
    public function update($id){
        //TO-DO
    }
    
    public function delete(){
        //TO-DO
    }
}
