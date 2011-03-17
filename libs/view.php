<?php
	class View{
		function __construct(){}
		
		public function show($name, $vars = array()){
			//$name es el nombre de la plantilla a renderizar
			//$vars son las variables a pasarle a esa plantilla (diccionario)
			
			//Se consigue la instancia del singleton Config
			$config = Config::singleton();
			
			//Formamos la ruta de la plantilla
			$path = $config->get('viewsFolder') . $name;
			
			//Si no existe el fichero damos un error
			if(!file_exists($path))
			{
				trigger_error("Template $path doesn't", E_USER_NOTICE);
				return false;
			}
			
			if(is_array($vars))
			{
				foreach($vars as $key => $value)
				{
					$$key = $value;
				}
			}
			
			//Incluimos la plantilla
			include($path);
		}
	}
/* Uso:
	$vista = new View();
	$vista->show('listado.php', array("nombre" => "juan"));
*/
?>
	