<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/1999/xhtml" >

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>EDIT TITULACIONES</title>	
</head>
<body>

  <?php echo form_open('titulaciones/update/'.$titulacion->id_titulacion); ?>
    Código: <input type="text" name="codigo" value="<?=$titulacion->codigo ?>" /><br />
    Nombre: <input type="text" name="nombre" size="50" value="<?= $titulacion->nombre ?>" /><br />
    Créditos: <input type="text" name="creditos" value="<?=$titulacion->creditos ?>" /><br />
    <input type="submit" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar') ?><br />
    <?php echo form_close(); ?>
</body>
</html>
