<?php echo form_open($action); ?>

<!-- Está todo con inputs de texto, hay que cambiar algunos campos que no tienen sentido con este tipo de inputs -->
<div class="field">
  <label for="tipo_evento">Tipo de evento:</label>
  <?= form_dropdown('tipo_evento', $options, $evento->tipo_evento); ?>
</div>

<div class="field">
    <label for="duracion">
        Duración del evento:
    </label>
    <?= form_dropdown('duracion', array('fecha_individual' => 'Fecha individual', 'rango' => 'Rango de fechas')) ?>
</div>

<div class="field">
    <label for="fecha_inicio">
        Fecha de inicio:
    </label>
    <?= form_input(); ?>
</div>
<div class="field">
    <label for="fecha_fin">
        Fecha de finalización:
    </label>
    <?= form_input(); ?>
</div>
<div class="actions">
   <input type="submit" id="submit_titulacion" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar', 'id="canceltitulacion"'); ?>
   
</div>


<?php echo form_close(); ?>