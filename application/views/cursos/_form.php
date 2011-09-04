<?php echo form_open($action); ?>

<!-- Está todo con inputs de texto, hay que cambiar algunos campos que no tienen sentido con este tipo de inputs -->
<div class="field">
  <label for="inicio_semestre1">Fechas primer semestre:</label>
  <input type="text" name="inicio_semestre1_format" class="datepicker_cursos"/>
  <input type="hidden" name="inicio_semestre1" id="inicio_semestre1" value="<?= $curso->inicio_semestre1 ?>" />
  -
  <input type="text" name="fin_semestre1_format" class="datepicker_cursos"/>
  <input type="hidden" name="fin_semestre1" id="fin_semestre1" value="<?= $curso->fin_semestre1 ?>" />
</div>
<div class="field">
  <label for="inicio_semestre2">Fechas segundo semestre:</label>
  <input type="text" name="inicio_semestre2_format" class="datepicker_cursos"/>
  <input type="hidden" name="inicio_semestre2" id="inicio_semestre2" value="<?= set_value("inicio_semestre2", $curso->inicio_semestre2) ?>" />
  -
  <input type="text" name="fin_semestre2_format" class="datepicker_cursos"/>
  <input type="hidden" name="fin_semestre2" id="fin_semestre2" value="<?= set_value("fin_semestre2", $curso->fin_semestre2) ?>" />
</div>
<div class="field">
  <label for="inicio_examenes_enero">Fechas exámenes de enero:</label>
  <input type="text" name="inicio_examenes_enero_format" class="datepicker_cursos"/>
  <input type="hidden" name="inicio_examenes_enero" id="inicio_examenes_enero" value="<?= set_value("inicio_examenes_enero", $curso->inicio_examenes_enero) ?>" />
  -
  <input type="text" name="fin_examenes_enero_format" class="datepicker_cursos"/>
  <input type="hidden" name="fin_examenes_enero" id="fin_examenes_enero" value="<?= set_value("fin_examenes_enero", $curso->fin_examenes_enero) ?>" />
</div>
<div class="field">
  <label for="inicio_examenes_junio">Fechas exámenes de junio:</label>
  <input type="text" name="inicio_examenes_junio_format" class="datepicker_cursos"/>
  <input type="hidden" name="inicio_examenes_junio" id="inicio_examenes_junio" value="<?= set_value("inicio_examenes_junio", $curso->inicio_examenes_junio) ?>" />
  -
  <input type="text" name="fin_examenes_junio_format" class="datepicker_cursos"/>
  <input type="hidden" name="fin_examenes_junio" id="fin_examenes_junio" value="<?= set_value("fin_examenes_junio", $curso->fin_examenes_junio) ?>" />
</div>
<div class="field">
  <label for="inicio_examenes_sept">Fechas exámenes de septiembre:</label>
  <input type="text" name="inicio_examenes_sept_format" class="datepicker_cursos"/>
  <input type="hidden" name="inicio_examenes_sept" id="inicio_examenes_sept" value="<?= set_value("inicio_examenes_sept", $curso->inicio_examenes_sept)?>" />
  -
  <input type="text" name="fin_examenes_sept_format" class="datepicker_cursos"/>
  <input type="hidden" name="fin_examenes_sept" id="fin_examenes_sept" value="<?= set_value("fin_examenes_sept", $curso->fin_examenes_sept) ?>" />
</div>
<div class="field">
  <label for="num_semanas_teoria">Número de semanas de teoría:</label>
  <input type="text" name="num_semanas_teoria" value="<?= set_value("num_semanas_teoria", $curso->num_semanas_teoria) ?>" />
</div>
<div class="field">
    <label for="horas_por_credito">Horas por crédito:</label>
    <input type="text" name="horas_por_credito" value="<?= set_value("horas_por_credito", $curso->horas_por_credito) ?>" />
</div>
<div class="field">
    <label for="slot_mínimo">Duración mínima de una clase:</label>
    <input type="text" name="slot_minimo" value="<?= set_value("slot_minimo", $curso->slot_minimo) ?>" />
</div>
<div class="field">
    <label for="hora_inicial">Hora inicial de clases:</label>
    <input type="text" name="hora_inicial" value="<?= set_value("hora_inicial", $curso->hora_inicial) ?>" />
</div>
<div class="field">
    <label for="hora_final">Hora final de clases:</label>
    <input type="text" name="hora_final" value="<?= set_value("hora_final", $curso->hora_final) ?>" />
</div>
<div class="actions">
   <input type="submit" id="submit_titulacion" name="button_action" value="Enviar" /> | <?= anchor('titulaciones/index', 'Cancelar', 'id="canceltitulacion"'); ?>
   
</div>


<?php echo form_close(); ?>