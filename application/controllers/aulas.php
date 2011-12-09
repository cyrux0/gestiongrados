<?php
/**
 * Controlador para la gestión de aulas
 * 
 * @author Daniel I. Salazar Recio
 * @package Controller
 */
class Aulas extends MY_Controller
{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->_filter(array('add', 'create', 'index', 'exportar_ocupacion', 'delete'), array($this, 'authenticate'), 1);
    }
    
    /**
     * Crea un formulario para añadir un aula al sistema
     */
    public function add(){
        $aula = new Aula;
        $tipos = Doctrine::getTable('Actividad')->findAll();
        
        $this->load->view('aulas/add', array('aula' => $aula, 'tipos' => $tipos, 'action' => 'aulas/create'));
    }
    
    /**
     * Crea un aula con los datos pasados por POST
     */
    public function create(){
        $aula = new Aula;
        $aula->fromArray($this->input->post());
        $aula->save();
        redirect('aulas');
        
    }
    
    /**
     * Muestra un formulario con los datos de un aula para editarla
     * @param integer $id Identificador del aula a editar
     */
    public function edit($id)
    {
        $aula = Doctrine::getTable('Aula')->find($id);
        $tipos = Doctrine::getTable('Actividad')->findAll();
        if(!$aula) show_404();
        
        $this->load->view('aulas/add', array('aula' => $aula, 'tipos' => $tipos, 'action' => 'aulas/update/'.$id));
    }
    
    public function update($id)
    {
        $aula = Doctrine::getTable('Aula')->find($id);
        $aula->fromArray($this->input->post());
        $aula->save();
        redirect('aulas/index');
        
    }
    
    /**
     * Muestra un listado de todas las aulas en el sistema
     */
    public function index(){
        $aulas = Doctrine::getTable('Aula')->findAll();
        $this->load->view('aulas/index', array('aulas' => $aulas));
    }
    
    /**
     * Borra un aula del sistema eliminando todas las asociaciones con ella en las líneas de horario.
     * @param integer $id Identificador del aula a eliminar
     */
    public function delete($id){
        $aula = Doctrine::getTable('Aula')->find($id);
        Doctrine_Query::create()
                ->delete('AulaActividad')
                ->addWhere('id_aula = ?', array($id))
                ->execute();
        $aula->unlink('lineashorario');
        $aula->save();
        $aula->delete();
        redirect('aulas/index');
    }
    
    /**
     *
     * @param integer $id Identificador del aula a exportar
     * @param integer $id_curso Identificador del curso que se quiere exportar
     * @param string $semestre Semestre que se quiere exportar
     * @param integer $num_semana Número de la semana que se quiere exportar
     */
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
