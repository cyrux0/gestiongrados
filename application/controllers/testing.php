<?php

class Testing extends CI_Controller{
  
  public function getpath(){
    Doctrine_Core::generateModelsFromDb('application/test_models', array('default'), array('generateTableClasses' => true));
  }
  
  

}