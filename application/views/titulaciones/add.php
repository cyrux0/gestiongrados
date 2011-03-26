<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/1999/xhtml" >

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>ADD TITULACIONES</title>	
</head>
<body>
  <?php echo form_open('titulaciones/create'); ?>
    Código: <input type="text" name="codigo" value="" /><br />
    Nombre: <input type="text" name="nombre" value="" /><br />
    Créditos: <input type="text" name="creditos" value="" /><br />
    <input type="submit" name="button_action" value="Enviar" /><br />
    
    <?php echo form_close(); ?>
</body>
</html>
