<?php echo form_open($action); ?>


<div class="field">
  <label for="nombre_evento">Nombre del evento:</label>
  <?= form_input('nombre_evento', set_value('nombre_evento', $evento->nombre_evento)) ?>
</div>
<div class="field">
  <label for="tipo_evento">Tipo de evento:</label>
  <?= form_dropdown('tipo_evento', $options, $evento->tipo_evento); ?>
</div>
<?= form_hidden('curso_id', $evento->curso_id) ?>
<div class="field">
    <label for="fecha_individual">
        Feche individual:
    </label>
    <?= form_checkbox('fecha_individual', $evento->fecha_individual); ?>
</div>
<div class="field">
    <label for="fecha_inicial">
        Fecha de inicio:
    </label>
    <?= form_input('fecha_inicial_format', '', 'class="datepicker_eventos" style="display:none"'); ?>
    <input type="text" id="fecha_inicial" name="fecha_inicial" value="<?= set_value('fecha_inicial', $evento->fecha_inicial) ?>" />
</div>
<div class="field">
    <label for="fecha_final">
        Fecha de finalización:
    </label>
    <?= form_input('fecha_final_format', '', 'class="datepicker_eventos" style="display:none"'); ?>
    <input type="text" id="fecha_final" name="fecha_final" value="<?= set_value('fecha_final', $evento->fecha_final) ?>" />
</div>
<div class="actions">
   <input type="submit" id="submit_titulacion" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar', 'id="canceltitulacion"'); ?>
   
</div>


<?php echo form_close(); ?>