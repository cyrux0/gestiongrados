<?php

class Admin extends CI_Controller{
  
  public function generatemodels(){
    Doctrine_Core::generateModelsFromDb('application/test_models', array('default'), array('generateTableClasses' => true));
  }
  
  public function generate_yaml(){
    Doctrine_Core::generateYamlFromDb('application/schema/schema.yml');

  }


  public function generate_migrations(){
        Doctrine_Core::generateMigrationClass('Migration', 'application/migrations');
        Doctrine_Core::generateMigrationsFromDb('application/migrations');

      
  }
  
  public function restaurar()
  {
      $this->layout ='';
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';
        $config['max_size']	= '100';

        $this->load->library('upload', $config);
        if(!$this->upload->do_upload()){
            $error = array('error' => $this->upload->display_errors(), 'action' => 'admin/restaurar');
            $this->load->view('PlanDocente/from_file', $error);
        }else{
            $this->load->database();
            Doctrine_Core::dropDatabases();
            Doctrine_Core::createDatabases();
            $data = $this->upload->data();
            $query_array = explode(";", file_get_contents($data['full_path']));
            //$this->load->library('db');
            foreach($query_array as $query)
            {
                mysql_query($query);
            }
        }    
  }
  
    public function restaurar_backup()
    {
        $this->layout = '';
        $error = array('action' => 'admin/restaurar');
        $this->load->view('PlanDocente/from_file', $error);

    }
  
  public function backup()
  {
      $prefs = array(
          'format' => 'txt',
          'add_drop' => TRUE,
          'add_insert' => TRUE,
      );
      $this->load->dbutil();
      $backup =& $this->dbutil->backup($prefs);
      $this->load->helper('download');
      force_download('backup-'. date("d-m-Y") . ".sql", $backup);
  }
  
  public function reset(){
    Doctrine_Core::dropDatabases();
    Doctrine_Core::createDatabases();
    //Doctrine_Core::generateModelsFromYaml('application/schema/schema.yml', 'application/test_models');
    Doctrine_Core::createTablesFromModels('application/models');
    
  }
  
    public function load_fixtures(){
        echo "This will delete all existing data";
        Doctrine_Manager::connection()->execute('SET FOREIGN_KEY_CHECKS = 0');
        Doctrine::loadData(APPPATH . '/fixtures');
    }
}