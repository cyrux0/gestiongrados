<?php

class CargasGlobales extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->globales_table = Doctrine::getTable('CargaGlobal');
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
    $this->layout = '';
  }



  public function create(){
    $global = new CargaGlobal;
    $global->curso_id = 3;
    $global->fromArray($this->input->post());
    $global->save();
    redirect('titulaciones/index');
  }
  
  public function edit($id){
    $global = $this->globales_table->find($id);
    $action = '/cargasglobales/update/' . $id;
    $data['data'] = array('result' => $global, 'action' => $action);
    $data['nombre_asignatura'] = $this->asignaturas_table->find($global->asignatura_id)->nombre;
    $data['page_title'] = 'Editando carga global';
    $this->load->view('cargaglobal/edit', $data);
  }

   public function update($id){
    $global = $this->globales_table->find($id);
    $global->fromArray($this->input->post());
    $global->save();
    redirect('titulaciones/show/' . $global->Asignatura->titulacion_id);
  }

}

/* Fin del archivo cargasglobales.php */
