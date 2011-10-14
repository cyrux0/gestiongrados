<?php

class Admin extends CI_Controller{
  
  public function generatemodels(){
    Doctrine_Core::generateModelsFromDb('application/test_models', array('default'), array('generateTableClasses' => true));
  }
  
  public function generate_yaml(){
    Doctrine_Core::generateYamlFromDb('application/schema/schema.yml');

  }


  public function generate_migrations(){
      try{
        Doctrine_Core::generateMigrationClass('Migration', 'application/migrations');
        Doctrine_Core::generateMigrationsFromDb('application/migrations');
      }catch(Doctrine_Migration_Exception $e){
          echo "juan";
      }
      
  }
  public function reset(){
    //Doctrine_Core::dropDatabases();
    //Doctrine_Core::createDatabases();
    //Doctrine_Core::generateModelsFromYaml('application/schema/schema.yml', 'application/test_models');
    Doctrine_Core::createTablesFromModels('application/models');
    
  }
  
    public function load_fixtures(){
        echo "This will delete all existing data";
        Doctrine_Manager::connection()->execute('SET FOREIGN_KEY_CHECKS = 0');
        Doctrine::loadData(APPPATH . '/fixtures');
    }
}