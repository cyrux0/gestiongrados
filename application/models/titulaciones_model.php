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
}