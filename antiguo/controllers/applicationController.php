<?php
class ApplicationController{
  static function main(){
    //Incluimos clases
    
    require 'libs/config.php';
    require 'libs/SPDO.php';
    require 'libs/view.php';
    require 'libs/controllerBase.php';
    require 'libs/modelBase.php';
    require 'config.php';
    
    //Formamos el nombre del controlador por defecto o el que se pida
    if(!empty($_GET['controller']))
      $controllerName = $_GET['controller'] . 'Controller';
    else
      $controllerName = "TitulacionesController";
    
    if(!empty($_GET['action']))
      $actionName = $_GET['action'];
    else
      $actionName = "index";
    
    $controllerPath = $config->get('controllersFolder') . $controllerName . '.php';
    
    if(is_file($controllerPath))
      require $controllerPath;
    else
      die('El controlador no existe - 404 not found ' . $controllerPath );
    
    if(!is_callable(array($controllerName, $actionName))){
      trigger_error($controllerName . '->' . $actionName . ' no existe', E_USER_NOTICE);
      return false;
    }
    $params = $_GET;
    $controller = new $controllerName();
    $controller->$actionName($params);
  }
}

?>
