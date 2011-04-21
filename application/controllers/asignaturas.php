<?php
class Asignaturas extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
    $this->titulaciones_table = Doctrine::getTable('Titulacion');
  }

  public function add_to($id){
    $data['nombre_titulacion'] = $this->titulaciones_table->find($id)->nombre;
    $result = new Asignatura;
    $action = 'asignaturas/create/'.$result->id;    
	$data['data'] = array('result' => $result, 'hidden' => array('titulacion' => $id), 'action' => $action );
    $this->load->view('asignaturas/add', $data);
  }


  public function create(){
    $asignatura = new Asignatura;
    $asignatura->codigo = $this->input->post('codigo');
    $asignatura->nombre = $this->input->post('nombre');
    $asignatura->creditos = $this->input->post('creditos');
    $asignatura->horas_presen = $this->input->post('horas_presen');
    $asignatura->horas_no_presen = $this->input->post('horas_no_presen');
    $asignatura->materia = $this->input->post('materia');
    $asignatura->departamento = $this->input->post('departamento');
    $titulacion = $this->titulaciones_table->find($this->input->post('titulacion'));
    $titulacion->asignaturas[] = $asignatura;
    $titulacion->save();
    redirect('titulaciones/show/'.$this->input->post('titulacion'));
  }

  public function edit($id){
    $result = $this->asignaturas_table->find($id);
    $action = 'asignaturas/update/'.$result->id;    
    $data['data'] = array('result' => $result, 'action' => $action);
    $data['nombre_asignatura'] = $result->nombre;
    $this->load->view('asignaturas/edit', $data);
  }

  public function update($id){
    $asignatura = $this->asignaturas_table->find($id);
    $asignatura->codigo = $this->input->post('codigo');
    $asignatura->nombre = $this->input->post('nombre');
    $asignatura->creditos = $this->input->post('creditos');
    $asignatura->horas_presen = $this->input->post('horas_presen');
    $asignatura->horas_no_presen = $this->input->post('horas_no_presen');
    $asignatura->materia = $this->input->post('materia');
    $asignatura->departamento = $this->input->post('departamento');
    $asignatura->save();
    redirect('titulaciones/show/'.$asignatura->titulacion_id);
  }
  
  public function delete($id){
    $record = $this->asignaturas_table->find($id);
    $titulacion_id = $record->titulacion_id;
    $record->delete();
    redirect('titulaciones/show/'.$titulacion_id);
  }
}

/* Fin archivo asignaturas.php */
