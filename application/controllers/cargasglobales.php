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

  public function create(){
    $global = new CargaGlobal;
    $global->horas_teoria = $this->input->post('horas_teoria');
    $global->creditos_teoria = $this->input->post('creditos_teoria');
    $global->grupos_teoria = $this->input->post('grupos_teoria');
    $global->horas_lab = $this->input->post('horas_lab');
    $global->creditos_lab = $this->input->post('creditos_lab');
    $global->grupos_lab = $this->input->post('grupos_lab');
    $global->horas_problemas = $this->input->post('horas_problemas');
    $global->creditos_problemas = $this->input->post('creditos_problemas');
    $global->grupos_problemas = $this->input->post('grupos_problemas');
    $global->horas_informatica = $this->input->post('horas_informatica');
    $global->creditos_informatica = $this->input->post('creditos_informatica');
    $global->grupos_informatica = $this->input->post('grupos_informatica');
    $global->horas_campo = $this->input->post('horas_campo');
    $global->creditos_campo = $this->input->post('creditos_campo');
    $global->grupos_campo = $this->input->post('grupos_campo');
    
    $asignatura = $this->asignaturas_table->find($this->input->post('asignatura'));
    $asignatura->CargasGlobales[] = $global;
    $asignatura->save();
    $global->save();
    redirect('titulaciones/index');
  }
  

}
