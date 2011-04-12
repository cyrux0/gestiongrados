  <?php echo form_open('titulaciones/create'); ?>
  Código: <input type="text" name="codigo" value="<?= $titulacion->codigo ?>" /><br />
  Nombre: <input type="text" name="nombre" value="<?= $titulacion->nombre ?>" /><br />
  Créditos: <input type="text" name="creditos" value="<?= $titulacion->creditos ?>" /><br />
  <input type="submit" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar') ?><br />
  
  <?php echo form_close(); ?>
