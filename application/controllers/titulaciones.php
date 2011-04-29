<?php
class Titulaciones extends CI_Controller{
  
  function __construct(){
    parent::__construct();
    $this->titulaciones_table = Doctrine::getTable('Titulacion');
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
  }
  
  public function index(){
    $this->layout = '';
    $titulaciones = $this->titulaciones_table->findAll();

    //Conseguimos los items mediante el modelo
    $data['titulaciones'] = $titulaciones;
    
    //Mostramos
    
    $this->load->view('titulaciones/index', $data);
  }

  public function add(){
    //Mostramos vista
    $titulacion = new Titulacion();
    
    $this->load->view('titulaciones/add', array('data' => array('titulacion' => $titulacion)));
  }

  public function create(){
    $titulacion = new Titulacion;
    $titulacion->creditos = $this->input->post('creditos');
    $titulacion->nombre = $this->input->post('nombre');
    $titulacion->codigo = $this->input->post('codigo');
    $titulacion->save();
    redirect('titulaciones/index');
  }

  public function delete($id){
    $titulacion = $this->titulaciones_table->find($id);
    $titulacion->asignaturas->delete();
    $titulacion->delete();
    redirect('titulaciones/index');
  }

  public function edit($id){
    $titulacion = $this->titulaciones_table->find($id);
    $data['titulacion'] = $titulacion;
   
    $this->load->view('titulaciones/edit', $data);
  }

  public function update($id){
    $titulacion = $this->titulaciones_table->find($id);
    $titulacion->codigo = $this->input->post('codigo');
    $titulacion->nombre = $this->input->post('nombre');
    $titulacion->creditos = $this->input->post('creditos');
    $titulacion->save();
    redirect('titulaciones/index');
  }

  public function show($id){
    $data['asignaturas'] = $this->asignaturas_table->findByTitulacion_id($id);
    $data['titulacion'] = $this->titulaciones_table->find($id);
    
    $this->load->view('titulaciones/show', $data);
  }
  
}

?>