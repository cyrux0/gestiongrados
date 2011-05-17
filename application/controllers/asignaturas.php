<?php
class Asignaturas extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
    $this->titulaciones_table = Doctrine::getTable('Titulacion');
    $this->layout = '';
  }

  public function add_to($id){
    $data['nombre_titulacion'] = $this->titulaciones_table->find($id)->nombre;
    $asignatura = new Asignatura;
    $asignatura->titulacion_id = $id;
    $action = 'asignaturas/create/' . $asignatura->id;    
    $data['data'] = array('result' => $asignatura, 'action' => $action );
    $data['page_title'] = 'Añadir asignatura';
    $this->load->view('asignaturas/add', $data);
  }


  public function create(){
    $asignatura = new Asignatura;
    $asignatura->fromArray($this->input->post());
    $asignatura->save();
    redirect('titulaciones/show/' . $this->input->post('titulacion_id'));
  }

  public function edit($id){
    $asignatura = $this->asignaturas_table->find($id);
    $action = 'asignaturas/update/' . $asignatura->id;    
    $data['data'] = array('result' => $asignatura, 'action' => $action);
    $data['nombre_asignatura'] = $asignatura->nombre;
    $data['page_title'] = 'Editando asignatura';
    $this->load->view('asignaturas/edit', $data);
  }

  public function update($id){
    $asignatura = $this->asignaturas_table->find($id);
    $asignatura->fromArray($this->input->post());
    $asignatura->save();
    redirect('titulaciones/show/' . $asignatura->titulacion_id);
  }
  
  public function delete($id){
    $asignatura = $this->asignaturas_table->find($id);
    $titulacion_id = $asignatura->titulacion_id;
    $asignatura->delete();
    redirect('titulaciones/show/' . $titulacion_id);
  }
}

/* Fin archivo asignaturas.php */
