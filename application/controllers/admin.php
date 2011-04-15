<?php

class Admin extends CI_Controller{
  
  public function getpath(){
    Doctrine_Core::generateModelsFromDb('application/test_models', array('default'), array('generateTableClasses' => true));
  }
  
  public function generate_yaml(){
    Doctrine_Core::generateYamlFromModels('application/schema/schema.yml', 'application/models');

  }

  public function reset(){
    Doctrine_Core::dropDatabases();
    Doctrine_Core::createDatabases();
    //    Doctrine_Core::generateModelsFromYaml('application/schema/schema.yml', 'application/models');
    Doctrine_Core::createTablesFromModels('application/models');
  }
  

}