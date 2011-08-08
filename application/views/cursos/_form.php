<?php echo form_open($action); ?>

<!-- Está todo con inputs de texto, hay que cambiar algunos campos que no tienen sentido con este tipo de inputs -->
<div class="field">
  <label for="inicio_semestre1">Fechas primer semestre:</label>
  <input type="text" name="fecha_inicio_semestre1" value="<?= $curso->inicio_semestre1 ?>" class="datepicker"/>
  -
  <input type="text" name="fecha_fin_semestre1" value="<?= $curso->fin_semestre1 ?>" class="datepicker"/>
</div>
<div class="field">
  <label for="inicio_semestre2">Fechas segundo semestre:</label>
  <input type="text" name="fecha_inicio_semestre2" value="<?= $curso->inicio_semestre2 ?>" class="datepicker"/>
  -
  <input type="text" name="fecha_fin_semestre2" value="<?= $curso->fin_semestre2 ?>" class="datepicker"/>
</div>
<div class="field">
  <label for="inicio_examenes_enero">Fechas exámenes de enero:</label>
  <input type="text" name="fecha_inicio_examenes_enero" value="<?= $curso->inicio_examenes_enero ?>" class="datepicker"/>
  -
  <input type="text" name="fecha_fin_examenes_enero" value="<?= $curso->fin_examenes_enero ?>" class="datepicker"/>
</div>
<div class="field">
  <label for="inicio_examenes_junio">Fechas exámenes de junio:</label>
  <input type="text" name="fecha_inicio_examenes_junio" value="<?= $curso->inicio_examenes_junio?>" class="datepicker"/>
  -
  <input type="text" name="fecha_fin_examenes_junio" value="<?= $curso->fin_examenes_junio ?>" class="datepicker"/>
</div>
<div class="field">
  <label for="inicio_examenes_sept">Fechas exámenes de septiembre:</label>
  <input type="text" name="fecha_inicio_examenes_sept" value="<?= $curso->inicio_examenes_sept?>" class="datepicker"/>
  -
  <input type="text" name="fecha_fin_examenes_sept" value="<?= $curso->fin_examenes_sept ?>" class="datepicker"/>
</div>
<div class="field">
  <label for="num_semanas_teoria">Número de semanas de teoría:</label>
  <input type="text" name="num_semanas_teoria" value="<?= $curso->num_semanas_teoria ?>" />
</div>
<div class="field">
    <label for="horas_por_credito">Horas por crédito:</label>
    <input type="text" name="horas_por_credito" value="<?= $curso->horas_por_credito ?>" />
</div>
<div class="field">
    <label for="slot_mínimo">Duración mínima de una clase:</label>
    <input type="text" name="slot_minimo" value="<?= $curso->slot_minimo ?>" />
</div>
<div class="field">
    <label for="hora_inicial">Hora inicial de clases:</label>
    <input type="text" name="hora_inicial" value="<?= $curso->hora_inicial ?>" />
</div>
<div class="field">
    <label for="hora_final">Hora final de clases:</label>
    <input type="text" name="hora_final" value="<?= $curso->hora_final ?>" />
</div>
<div class="actions">
   <input type="submit" id="submit_titulacion" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar', 'id="canceltitulacion"'); ?>
   
</div>


<?php echo form_close(); ?>