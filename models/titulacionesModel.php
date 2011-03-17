<?php

class TitulacionesModel
{
	protected $db;
	
	public function __construct(){
		//Traemos la única instancia de PDO
		$this->db = SPDO::singleton();
	}
	
	public function listadoTotal(){
		//Consulta de todas las titulaciones
		$consulta = $this->db->prepare('SELECT * FROM titulaciones');
		$consulta->execute();
		//Devolvemos la colección para pasarsela al controlador o vista
		return $consulta;
	
	}
}
?>
