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
    //require 'models/titulacionesModel.php';
    
    /* //Creamos una instancia del modelo */
    /* $titulaciones = new TitulacionesModel(); */

    /* $pruebas = array('author' => 'paco', 'title' => 'asdasd'); */
    /* $func = create_function('$a','return ":".$a;'); */
    /* $pruebas2 = array_map($func, array_keys($pruebas)); */
    /* echo implode(",",$pruebas2); */
    $this->view->show("addTitulaciones.php",array());
  }
  
  public function create()
  {
    require 'models/titulacionesModel.php';

    //Creamos una instancia del modelo
    $titulaciones = new TitulacionesModel();
    $accion = $_POST['button_action'];
    //echo $accion.'asd';
    if($accion == 'Cancelar'){
      $data['titulaciones'] = $titulaciones->listadoTotal();
      $this->view->show("indexTitulaciones.php",$data);
    }else{
      $params = array();
      $params['codigo'] = $_POST['codigo'];
      $params['nombre'] = $_POST['nombre'];
      $params['creditos'] = $_POST['creditos'];
      $titulaciones->create($params);
      $data['titulaciones'] = $titulaciones->listadoTotal();
      $this->view->show("indexTitulaciones.php",$data);
      $this->index();
    }
  }
}

?>
