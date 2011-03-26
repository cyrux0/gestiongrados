<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/1999/xhtml" >

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>EDIT TITULACIONES</title>	
</head>
<body>
  <?php $action = "?controller=Titulaciones&action=update&id=".$id; ?>
  
  <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
    Código: <input type="text" name="codigo" value="<?=$titulacion['codigo'] ?>" /><br />
    Nombre: <input type="text" name="nombre" size="50" value="<?= $titulacion['nombre'] ?>" /><br />
    Créditos: <input type="text" name="creditos" value="<?=$titulacion['creditos']?>" /><br />
    <input type="submit" name="button_action" value="Enviar" /> | <a href="index.php">Cancelar</a><br />
  </form>
</body>
</html>
