<?php echo form_open($action); ?>
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
<div class="field">
    <label for="num_cursos">Número de cursos:</label>
    <input type="text" name="num_cursos" value="<?= $titulacion->num_cursos ?>" />
</div>
<div class="actions">
   <input type="submit" id="submit_titulacion" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar', 'id="canceltitulacion"'); ?>
   
</div>


<?php echo form_close(); ?>
