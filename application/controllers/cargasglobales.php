<?php

class CargasGlobales extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->globales_table = Doctrine::getTable('CargaGlobal');
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
  }

  public function add($asignatura_id){
    $global = new CargaGlobal;
    $global->asignatura_id = $asignatura_id;
    $action = 'cargasglobales/create/'.$global->asignatura_id;
    $data['data'] = array('result' => $global, 'action' => $action);
    $data['nombre_asignatura'] = $this->asignaturas_table->find($asignatura_id)->nombre;
    $this->load->view('cargaglobal/add', $data);
  }
}
