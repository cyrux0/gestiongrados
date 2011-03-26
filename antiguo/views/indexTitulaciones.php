<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>INDEX TITULACIONES</title>
</head>
<body>
<table>
	<tr>
		<th>ID</th><th>NOMBRE</th><th>CRÉDITOS</th>
	</tr>
	<?php $titulaciones_array = $titulaciones->fetchAll();
		foreach($titulaciones_array as $item): ?>
		<tr><td><?= $item['codigo']?></td><td><?= $item['nombre']?></td>
		<td><a href="<?= 'index.php?controller=Titulaciones&action=edit&id='.$item['id_titulacion']; ?>">Editar</a></td>
<td><a href="<?= 'index.php?controller=Titulaciones&action=delete&id='.$item['id_titulacion']; ?>" onclick = "return confirm('¿Estás seguro?')">Eliminar</a></td></tr>
	<?php endforeach; ?>
	
</table>
<a href="index.php?controller=Titulaciones&action=add">Añadir una nueva titulación</a>
</body>
</html>
