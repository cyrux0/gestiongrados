<?php echo form_open('titulaciones/create'); ?>
<form>
<label for="codigo">Código:</label>
<input type="text" name="codigo" value="<?= $titulacion->codigo ?>" /><br />
<label for="nombre">Nombre:</label> <input type="text" name="nombre" value="<?= $titulacion->nombre ?>" /><br />
<label for="creditos">Créditos:</label> <input type="text" name="creditos" value="<?= $titulacion->creditos ?>" /><br />
<input type="submit" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar') ?><br />

<?php echo form_close(); ?>
