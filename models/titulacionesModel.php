<?php

class TitulacionesModel extends ModelBase
{

  public function listadoTotal(){
    //Consulta de todas las titulaciones
    $consulta = $this->db->prepare('SELECT * FROM titulaciones');
    $consulta->execute();

    //Devolvemos la colección para pasarsela al controlador o vista
    return $consulta;	
  }

  public function newelem($params){
    $paramsnames = implode(",", array_keys($params));
    $paramscolon = implode(",", array_map(create_function('$a','return ":".$a;'),array_keys($params)));
    
    $sql = "INSERT INTO titulaciones (".$paramsnames.") VALUES (".$paramscolon.")";
    $query = $this->db->prepare($sql);
    $query->execute();
  }
}
?>
