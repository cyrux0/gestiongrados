<?php

class TitulacionesModel extends ModelBase
{

  public static function listadoTotal(){
    //Consulta de todas las titulaciones
    
    $consulta = self::db()->prepare('SELECT * FROM titulaciones');
    $consulta->execute();

    //Devolvemos la colección para pasarsela al controlador o vista
    return $consulta;	
  }

  public static function create($params){
    $paramsnames = implode(",", array_keys($params));
    $paramscolon = implode(",", array_map(create_function('$a','return ":".$a;'),array_keys($params)));
    
    $sql = "INSERT INTO titulaciones (".$paramsnames.") VALUES (".$paramscolon.")";
    $query = self::db()->prepare($sql);
    $inserciones=array();
    foreach($params as $key => $value){
      $inserciones[':'.$key] = $value;
    }
    $query->execute($inserciones);
  }

  public static function update($id, $params){
    $sql = "UPDATE titulaciones SET ".implode(",",array_map(create_function('$a','return $a."=?";'),array_keys($params))) . "WHERE id_titulacion = " . $id;
    $query = self::db()->prepare($sql);
    $query->execute(array_values($params));
  }

  public static function find($id){
    $sql = "SELECT * FROM titulaciones WHERE id_titulacion = ".$id;
    $query = self::db()->prepare($sql);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
  }
  
  public static function delete($id){
    $sql = "DELETE FROM titulaciones WHERE id_titulacion = ".$id;
    $query = self::db()->prepare($sql);
    $query->execute();
  }
}
?>
