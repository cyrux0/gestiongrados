<?php
class TitulacionesController extends ControllerBase
{
  
  public function index(){
    require 'models/titulacionesModel.php';
    
    //Instancia del modelo
    $titulaciones = new TitulacionesModel();
    
    //Conseguimos los items
    $array_titulaciones = $titulaciones->listadoTotal();
    
    //Pasamos a la vista los datos en el array data
    $data['titulaciones'] = $array_titulaciones;
    $this->view->show("indexTitulaciones.php", $data);
    
  }
  
  public function add()
  {
    echo "En construcción: aquí iría el formulario para nuevas titulaciones";
  }
}

?>
