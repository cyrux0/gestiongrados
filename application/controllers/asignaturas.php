<?php
// La idea es, crear un formulario con campo oculto con la id de la titulación, luego en este controlador, existirán las acciones create(), update() y delete() o destroy(). En el controlador de Titulaciones estaría el index de asignaturas, la cosa es, el new y el edit donde ponerlos, seguramente aquí también, ya que no es como en el tutorial de Rails, aquí vamos a tener una página propia para las nuevas asignaturas no va a ser un form generado por jQuery (o sí?).
class Asignaturas extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('Asignatura_model');
    $this->load->model('Titulacion_model');
  }

  public function add_to($id){
    $data['nombre_titulacion'] = $this->Titulacion_model->find($id)->nombre;

    //Esto hay que hacerlo de otra forma
    $data['data'] = array('result' => array('nombre' => '',
					    'creditos' => '',
					    'codigo' => '',
					    'materia' => '',
					    'departamento' => '',
					    'horas_presen' => '',
					    'horas_no_presen' => ''),
			  'hidden' => $id,
			  'action' => 'asignaturas/create');

    $this->load->view('asignaturas/add', $data);
  }


  public function create(){
    $this->Asignatura_model->insert_new();
    redirect('titulaciones/index');
  }

  public function edit($id){
    $result = $this->Asignatura_model->find($id);
    $action = 'asignaturas/update/'.$result->id_asignatura;    
    $data['data'] = array('result' => $result, 'action' => $action);
    $data['nombre_asignatura'] = $result->nombre;
    
    $this->load->view('asignaturas/edit', $data);
  }

  public function update($id){
    $record = $this->Asignatura_model->find($id);
    $this->Asignatura_model->update($id);
    redirect('titulaciones/show/'.$record->id_titulacion);
  }
  
  public function delete($id){
    $record = $this->Asignatura_model->find($id);
    $this->Asignatura_model->delete($id);
    redirect('titulaciones/show/'.$record->id_titulacion);
  }
}

/* Fin archivo asignaturas.php */
