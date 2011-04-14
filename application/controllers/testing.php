<?php

class Testing extends CI_Controller{
  
  public function getpath(){
    Doctrine_Core::generateModelsFromDb('modelasos', array('default'), array('generateTableClasses' => true));
  }
  
  

}