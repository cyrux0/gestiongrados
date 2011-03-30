<?php

class Asignatura_model extends CI_Model{
  
  
  public function insert_new(){
    $fields = $this->db->list_fields('asignaturas');
    $values = array();
    foreach($fields as $field){
      $values[$field] = $_POST[$field];
    }
    $this->db->insert('asignaturas', $values);
  }
  
  public function find($id){
    $query = $this->db->get_where('asignaturas', array('id_asignatura' => $id), 1);
    return $query->row();
  }
}