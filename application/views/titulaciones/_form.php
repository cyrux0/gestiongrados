<?php echo form_open('titulaciones/create'); ?>
<div class="field">
  <label for="codigo">Código:</label>
  <input type="text" name="codigo" value="<?= $titulacion->codigo ?>" />
</div>
<div class="field">
  <label for="nombre">Nombre:</label>
  <input type="text" name="nombre" value="<?= $titulacion->nombre ?>" />
</div>
<div class="field">
  <label for="creditos">Créditos:</label>
  <input type="text" name="creditos" value="<?= $titulacion->creditos ?>" />
</div>
<div class="actions">
  <input type="submit" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar') ?>
</div>
<?php echo form_close(); ?>
