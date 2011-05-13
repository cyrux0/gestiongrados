<?php

class CargasSemanales extends CI_Controller{
  
  function __construct(){
    parent::__construct();
    $this->semanales_table = Doctrine::getTable('CargaSemanal');
  }

  public function add($id_global){
    $semanal = new CargaSemanal;
    $semanal->cargaglobal_id = $id_global;
    $action = 'cargassemanales/create/';
    $data['data'] = array('result' => $semanal, 'action' => $action);
    $this->load->view('cargassemanales/add', $data);
  }

  public function create(){
    $semanal = new CargaSemanal;
    $semanal->fromArray($this->input->post());
    $semanal->save();
    redirect('titulaciones/index');
  }

  public function edit($id){
    $semanal = $this->semanales_table->find($id);
    $action = 'cargassemanales/update/' . $id;
    $data['data'] = array('result' => $semanal, 'action' => $action);
    $this->load->view('cargassemanales/edit', $data);
  }

  public function update($id){
    $semanal = $this->semanales_table->find($id);
    $semanal->fromArray($this->input->post());
    $semanal->save();
  }
  

}

/* Fin del archivo cargassemanales.php */