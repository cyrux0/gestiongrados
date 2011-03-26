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
}