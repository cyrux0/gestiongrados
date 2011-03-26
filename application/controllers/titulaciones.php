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


}

?>