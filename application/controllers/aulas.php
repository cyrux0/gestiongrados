<?php

class Aulas extends MY_Controller
{
    function __construct(){
        parent::__construct();
        $this->layout = '';
    }
    
    public function add(){
        $aula = new Aula;
        $tipos = Doctrine::getTable('Actividad')->findAll();
        
        $this->load->view('aulas/add', array('aula' => $aula, 'tipos' => $tipos));
    }
    
    public function create(){
        $aula = new Aula;
        $aula->fromArray($this->input->post());
        $aula->save();
        redirect('aulas');
        
    }
    
    public function index(){
        $aulas = Doctrine::getTable('Aula')->findAll();
        $this->load->view('aulas/index', array('aulas' => $aulas));
    }
    
    public function delete($id){
        $aula = Doctrine::getTable('Aula')->find($id);
        $aula->delete();
    }
    
    public function exportar_ocupacion($id, $id_curso, $semestre, $num_semana)
    {
        // Extraemos el aula de la bd
        $curso = Doctrine::getTable('Curso')->find($id_curso);
        $ocupacion = $curso->getMatrizHorario('id_aula', $id, $semestre, $num_semana);
        $this->load->helper('importacion_csv_helper');
        $this->load->helper('download');
        exportador_csv('./application/downloads/temp.csv', $ocupacion);
        $data = file_get_contents('./application/downloads/temp.csv');
        $name = 'aula.csv';
        force_download($name, $data);
    }
}
