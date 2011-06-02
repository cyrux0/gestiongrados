<?php
class Titulaciones extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->titulaciones_table = Doctrine::getTable('Titulacion');
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
    $this->layout = '';
  }
  
  public function index(){
    
    $titulaciones = $this->titulaciones_table->findAll();
    
    //Conseguimos los items mediante el modelo
    $data['titulaciones'] = $titulaciones;
    $data['page_title'] = 'INDEX TITULACIONES';
    //Mostramos
    
    $this->load->view('titulaciones/index', $data);
  }

  public function add(){
    //Mostramos vista
    $titulacion = new Titulacion();
    if ($this->input->post('js')){
      unset($this->layout);
    }
	$action = 'titulaciones/create';
    $this->load->view('titulaciones/add', array('data' => array('titulacion' => $titulacion, 'action' => $action ), 'page_title' => 'ADD TITULACIONES'));
  }

  public function create(){
    $titulacion = new Titulacion;
    $titulacion->fromArray($this->input->post());
    $titulacion->save();
    
    if($this->input->post('remote')=="true"){
        unset($this->layout);
        $this->load->view('titulaciones/create', array('item' => $titulacion));
    }else
        redirect('titulaciones/index');
  }

  public function delete($id){
    $titulacion = $this->titulaciones_table->find($id);
    $titulacion->asignaturas->delete();
    $titulacion->delete();
    redirect('titulaciones/index');
  }

  public function edit($id){
    $titulacion = $this->titulaciones_table->find($id);
	$action = 'titulaciones/update/' . $id;
    $data['data'] = array('titulacion' => $titulacion, 'action' => $action);
    $data['page_title'] = 'EDIT TITULACIONES';
    $this->load->view('titulaciones/edit', $data);
  }

  public function update($id){
    $titulacion = $this->titulaciones_table->find($id);
    $titulacion->fromArray($this->input->post());
    $titulacion->save();
    redirect('titulaciones/index');
  }

  public function show($id){
    $data['asignaturas'] = $this->asignaturas_table->findByTitulacion_id($id);
    $data['titulacion'] = $this->titulaciones_table->find($id);
    $data['page_title'] = 'INDEX ASIGNATURAS';
    $this->load->view('titulaciones/show', $data);
  }
  
}

/* Fin del archivo titulaciones.php */