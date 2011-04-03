<?php echo doctype(); ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
  <title>ADD TITULACIONES</title>
</head>
<body>
  <?php echo form_open('titulaciones/create'); ?>
  Código: <input type="text" name="codigo" value="<?= $titulacion->codigo ?>" /><br />
  Nombre: <input type="text" name="nombre" value="<?= $titulacion->nombre ?>" /><br />
  Créditos: <input type="text" name="creditos" value="<?= $titulacion->creditos ?>" /><br />
  <input type="submit" name="button_action" value="Enviar" /><br />
  
  <?php echo form_close(); ?>
</body>
</html>
