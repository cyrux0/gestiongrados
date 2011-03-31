<?php
class Titulaciones extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('Titulacion_model');
    $this->load->model('Asignatura_model');
  }
  
  public function index(){
    //Cargamos el modelo
    
    //Conseguimos los items mediante el modelo
    $data['titulaciones'] = $this->Titulacion_model->list_all();

    //Mostramos
    
    $this->load->view('titulaciones/index', $data);
  }

  public function add(){
    //Mostramos vista

    $this->load->view('titulaciones/add');
  }

  public function create(){
        
    $this->Titulacion_model->insert_new();
    redirect('titulaciones/index');
  }

  public function delete($id){
    
    $this->Titulacion_model->delete_elem($id);
    redirect('titulaciones/index');
  }

  public function edit($id){
    $result = $this->Titulacion_model->find($id);
    $data['titulacion'] = $result;
   
    $this->load->view('titulaciones/edit', $data);
  }

  public function update($id){
    $this->Titulacion_model->update($id);
    redirect('titulaciones/index');
  }

  public function show($id){
    $data['asignaturas'] = $this->Asignatura_model->find_by_titulacion($id);
    $data['titulacion'] = $this->Titulacion_model->find($id);
    
    $this->load->view('titulaciones/show', $data);
  }


}

?>