<?php
require_once 'models/titulacionesModel.php';

class TitulacionesController extends ControllerBase
{
  
  public function index($params){
    
    //Conseguimos los items
    $array_titulaciones = TitulacionesModel::listadoTotal();
    
    //Pasamos a la vista los datos en el array data
    $data['titulaciones'] = $array_titulaciones;
    $this->view->show("indexTitulaciones.php", $data);
    
  }
  
  public function add($params)
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
  
  public function create($params)
  {

    //Averiguamos que botón se ha pulsado
    $accion = $_POST['button_action'];
    if($accion == 'Cancelar'){
      //Si el botón pulsado fue cancelar, redireccionamos al index
      header('Location: index.php?controller=Titulaciones&action=index');
    }else{
      //Creamos un vector con los parámetros que se usarán para crear el objeto
      $params = array();
      $params['codigo'] = $_POST['codigo'];
      $params['nombre'] = $_POST['nombre'];
      $params['creditos'] = $_POST['creditos'];
      //Llamamos a la función create del modelo
      TitulacionesModel::create($params);
      //Hacemos la redirección al index
      header('Location: index.php?controller=Titulaciones&action=index');
    }
  }

  public function edit($params){
    //Extraemos el elemento
    $id = $params['id'];
    
    $titulacion = TitulacionesModel::find($id); //TO-DO Refactorizar para que TitulacionesModel se llame simplemente Titulacion (inglés)
    
    $data['titulacion']=$titulacion;
    $data['id']=$id;
    $this->view->show('editTitulaciones.php', $data);
  }
    
  public function update($params){
    $id = $params['id'];
    //echo array_keys($_POST);
    
    
  }
}

?>
