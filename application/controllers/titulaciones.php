<?php
class Titulaciones extends CI_Controller{
  public function index(){
    //Cargamos el modelo
    $this->load->model('Titulaciones_model','',TRUE);
    //Conseguimos los items mediante el modelo
    $data['titulaciones'] = $this->Titulaciones_model->list_all();

    //Mostramos
    $this->load->helper('url');
    $this->load->view('titulaciones/index', $data);
  }

  public function add(){
    //Mostramos vista
    $this->load->helper('form');
    $this->load->view('titulaciones/add');
  }

  public function create(){
    $this->load->model('Titulaciones_model','',TRUE);    
    $this->Titulaciones_model->insert_new();
    $this->index();//Esto hace que en la url se muestre /create, hay que cambiarlo
  }

  public function delete($id){
    $this->load->model('Titulaciones_model','',TRUE);
    $this->Titulaciones_model->delete_elem($id);
    $this->index();
  }

  public function edit($id){
    $this->load->model('Titulaciones_model','', TRUE); //Ver si se puede cargar este modelo en el constructor
    $result = $this->Titulaciones_model->find($id);
    $data['titulacion'] = $result[0];
    $this->load->helper('form'); //Autocargar este helper
    $this->load->view('titulaciones/edit', $data);
  }

  public function update($id){
    $this->load->model('Titulaciones_model', '', TRUE);
    $this->Titulaciones_model->update($id);
    $this->index();
  }
}

?>