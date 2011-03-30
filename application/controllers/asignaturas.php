<?php
// La idea es, crear un formulario con campo oculto con la id de la titulación, luego en este controlador, existirán las acciones create(), update() y delete() o destroy(). En el controlador de Titulaciones estaría el index de asignaturas, la cosa es, el new y el edit donde ponerlos, seguramente aquí también, ya que no es como en el tutorial de Rails, aquí vamos a tener una página propia para las nuevas asignaturas no va a ser un form generado por jQuery (o sí?).
class Asignaturas extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('Asignatura_model');
  }

  public function index(){
    //
    // Falta:
    //  -Crear el modelo
    //  -Crear la tabla en la BD
    //  -Crear la vista
    //

    //$this->load->model('Asignaturas_model','',TRUE);
    //$data['asignaturas'] = $this->Titulaciones_model->list_all();

    //$this->load->view('asignaturas/index', $data);
  }

  public function create(){
    $this->Asignatura_model->insert_new();
    redirect('titulaciones/index');
  }

  public function edit($id){
    $result = $this->Asignatura_model->find($id);
    $data['asignatura'] = $result;
    $data['codigo'] = 123;
    $data['hidden'] = array('id_titulacion' => $result->id_titulacion);
    $data2['asignatura_form'] = $this->load->view('asignaturas/_form', $data, TRUE);
    
    $data2['nombre_asignatura'] = $result->nombre;
    $this->load->view('asignaturas/edit', $data2);
  }

  public function update(){
    //
  }
}

/* Fin archivo asignaturas.php */