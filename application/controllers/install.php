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
            $actividad = new Actividad();
            $actividad->id = 1;
            $actividad->descripcion = "Teoría";
            $actividad->identificador = "A";
            $actividad->id = 2;
            $actividad->descripcion = "Problemas";
            $actividad->identificador = "B";
            $actividad->id = 3;
            $actividad->descripcion = "Laboratorio";
            $actividad->identificador = "C";
            $actividad->id = 4;
            $actividad->descripcion = "Informática";
            $actividad->identificador = "D";
            $actividad->id = 5;
            $actividad->descripcion = "Prácticas de campo";
            $actividad->identificador = "E";
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
