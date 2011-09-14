<?php echo form_open($action,''); ?>
  <?php echo form_hidden('PlanDocente_id', $result->PlanDocente_id); ?>

  <label for="num_semana">Semana:</label>
  <?php echo form_input('num_semana', $result->num_semana); ?><br />

  <label for="horas_teoria">Horas de teoría:</label>
  <?php echo form_input('horas_teoria', $result->horas_teoria); ?><br />
  
  <label for="horas_lab">Horas de laboratorio:</label>
  <?php echo form_input('horas_lab', $result->horas_lab); ?><br />

  <label for="horas_problemas">Horas de problemas:</label>
  <?php echo form_input('horas_problemas', $result->horas_problemas); ?><br />  

  <label for="horas_informatica">Horas de informática:</label>
  <?php echo form_input('horas_informatica', $result->horas_informatica); ?><br />  

  <label for="horas_campo">Horas de prácticas de campo:</label>
  <?php echo form_input('horas_campo', $result->horas_campo); ?><br />  

  <?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/show/' . $result->PlanDocente->Asignatura->titulacion_id, 'Cancelar') ?>  
<?php echo form_close(); ?>
