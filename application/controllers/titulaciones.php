<?php
class Titulaciones extends CI_Controller{
  public function index(){
    //Cargamos el modelo
    $this->load->model('Titulaciones_model','',TRUE);
    //Conseguimos los items mediante el modelo
    $data['titulaciones'] = $this->Titulaciones_model->list_all();
    //Mostramos
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

}

?>