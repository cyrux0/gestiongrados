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
}
?>
