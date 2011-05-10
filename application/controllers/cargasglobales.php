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
    $action = 'cargasglobales/create/';
    $data['data'] = array('result' => $global, 'action' => $action);
    $data['nombre_asignatura'] = $this->asignaturas_table->find($asignatura_id)->nombre;
    $this->load->view('cargaglobal/add', $data);
  }

  public function create(){
    $global = new CargaGlobal;
    $global->creditos_teoria = $this->input->post('creditos_teoria');
    $global->grupos_teoria = $this->input->post('grupos_teoria');
    $global->creditos_lab = $this->input->post('creditos_lab');
    $global->grupos_lab = $this->input->post('grupos_lab');
    $global->creditos_problemas = $this->input->post('creditos_problemas');
    $global->grupos_problemas = $this->input->post('grupos_problemas');
    $global->creditos_informatica = $this->input->post('creditos_informatica');
    $global->grupos_informatica = $this->input->post('grupos_informatica');
    $global->creditos_campo = $this->input->post('creditos_campo');
    $global->grupos_campo = $this->input->post('grupos_campo');    

    $asignatura = $this->asignaturas_table->find($this->input->post('asignatura'));
    $asignatura->CargasGlobales[] = $global;
    $asignatura->save();
    $global->save();
    redirect('titulaciones/index');
  }
  
  public function edit($id){
    $global = $this->globales_table->find($id);
    $action = '/cargasglobales/update/'.$id;
    $data['data'] = array('result' => $global, 'action' => $action);
    $data['nombre_asignatura'] = $this->asignaturas_table->find($global->asignatura_id)->nombre;
    $this->load->view('cargaglobal/edit', $data);
  }

   public function update($id){
    $global = $this->globales_table->find($id);
    $global->creditos_teoria = $this->input->post('creditos_teoria');
    $global->grupos_teoria = $this->input->post('grupos_teoria');
    $global->creditos_lab = $this->input->post('creditos_lab');
    $global->grupos_lab = $this->input->post('grupos_lab');
    $global->creditos_problemas = $this->input->post('creditos_problemas');
    $global->grupos_problemas = $this->input->post('grupos_problemas');
    $global->creditos_informatica = $this->input->post('creditos_informatica');
    $global->grupos_informatica = $this->input->post('grupos_informatica');
    $global->creditos_campo = $this->input->post('creditos_campo');
    $global->grupos_campo = $this->input->post('grupos_campo');
    
    $global->save();
    redirect('titulaciones/index');
  }
 
 

}
