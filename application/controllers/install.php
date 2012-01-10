<?php

class Install extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        $this->layout = '';
    }
    
    public function index()
    {
        $conn = Doctrine_Manager::connection();
        try { $conn->execute("DESC users"); }
        catch (Exception $e) { 
            Doctrine_Core::dropDatabases();
            Doctrine_Core::createDatabases();
            //Doctrine_Core::generateModelsFromYaml('application/schema/schema.yml', 'application/test_models');
            Doctrine_Core::createTablesFromModels('application/models');
            $user = new User;
            $user->nombre = "Administrador";
            $user->apellidos = "ESI UCA";
            $user->email = "administrador@uca.es";
            $user->level = 0;
            $user->password = "12345";
            $user->save();
            Current_User::login("administrador@uca.es", "12345");
            $this->load->view('install/instalada');
        }
        
        $this->load->view('install/ya_instalada');
    }
    
    public function uninstall()
    {
        if($this->input->post('Si'))
        {
            Doctrine_Core::dropDatabases();
            Doctrine_Core::createDatabases();
            redirect('/');
        }
        
        $this->load->view('install/uninstall');
    }
}

?>
