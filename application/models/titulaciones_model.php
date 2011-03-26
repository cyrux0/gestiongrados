<?php

class Titulaciones_model extends CI_Model{
  var $nombre = '';
  var $creditos = 0;
  var $codigo = '';


  function __construct(){
    parent::__construct();
  }
  
  public function list_all(){
    $query = $this->db->get('titulaciones');
    return $query->result();
  }

  public function insert_new(){

    //Se usa _POST hay que cambiar eso por lo correspondiente
    $this->nombre = $_POST['nombre'];
    $this->creditos = $_POST['creditos'];
    $this->codigo = $_POST['codigo'];
    
    $this->db->insert('titulaciones',$this);
  }

  public function delete_elem($id){
    $this->db->delete('titulaciones', array('id_titulacion'=>$id));
  }
  
  public function find($id){
    $query = $this->db->get_where('titulaciones', array('id_titulacion'=>$id), 1);
    
    return $query->result();
  }

  public function update($id){
    $data['codigo'] = $_POST['codigo'];
    $data['nombre'] = $_POST['nombre'];
    $data['creditos'] = $_POST['creditos'];
    
    $this->db->where('id_titulacion', $id);
    $this->db->update('titulaciones', $data);
  }

}