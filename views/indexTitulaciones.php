<! DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

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
		<tr><td><?= $item['codigo']?></td><td><?= $item['nombre']?></td></tr>
	<?php endforeach; ?>
	
</table>
</body>
</html>
