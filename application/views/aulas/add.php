<?php echo form_open('aulas/create'); ?>
<div class="field">
  <label for="nombre">Nombre:</label>
  <input type="text" name="nombre" value="<?= set_value('nombre', $aula->nombre) ?>" />
</div>

<div class="field">
    <?= form_label('Tipos:', 'tipo') ?>
    <? foreach($tipos as $tipo): ?>
        <?= form_checkbox('actividades[]', $tipo->id) ?> <label class="checkbox_label"><?= $tipo->descripcion ?></label>
    <? endforeach; ?>
</div>

<div class="actions">
   <input type="submit" id="submit" name="button_action" value="Enviar" /> | <?= anchor('aulas', 'Cancelar'); ?>
   
</div>

<?php echo form_close(); ?>
