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

  public function find_by_titulacion($id)
  {
    $query = $this->db->get_where('asignaturas', array('id_titulacion' => $id), 1);
    return $query->result();
  }

  public function update($id){
    $fields = $this->db->list_fields('asignaturas');
    $values = array();
    foreach($fields as $field){
      if($_POST[$field])
	$values[$field] = $_POST[$field];
    }
    
    $this->db->where('id_asignatura', $id);
    $this->db->update('asignaturas', $values);
  }

  public function delete($id)
  {
    $this->db->delete('asignaturas', array('id_asignatura' => $id));
  }
}
