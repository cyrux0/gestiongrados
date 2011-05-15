<?php echo form_open($action,''); ?>
  <?php echo form_hidden('asignatura_id', $result->asignatura_id); ?>

  <label for="num_semanas">Número de semanas en el curso:</label>
  <?php echo form_input('num_semanas', $result->num_semanas); ?><br />

  <label for="creditos_teoria">Créditos de teoría:</label>
  <?php echo form_input('creditos_teoria', $result->creditos_teoria); ?><br />
  <label for="grupos_teoria">Grupos de teoría:</label>
  <?php echo form_input('grupos_teoria', $result->grupos_teoria); ?><br />
  
  <label for="creditos_lab">Créditos de laboratorio:</label>
  <?php echo form_input('creditos_lab', $result->creditos_lab); ?><br />
  <label for="grupos_lab">Grupos de laboratorio:</label>
  <?php echo form_input('grupos_lab', $result->grupos_lab); ?><br />

  <label for="creditos_problemas">Créditos de problemas:</label>
  <?php echo form_input('creditos_problemas', $result->creditos_problemas); ?><br />
  <label for="grupos_problemas">Grupos de problemas:</label>
  <?php echo form_input('grupos_problemas', $result->grupos_problemas); ?><br />  

  <label for="creditos_informatica">Créditos de informática:</label>
  <?php echo form_input('creditos_informatica', $result->creditos_informatica); ?><br />
  <label for="grupos_informatica">Grupos de informática:</label>
  <?php echo form_input('grupos_informatica', $result->grupos_informatica); ?><br />  

  <label for="creditos_campo">Créditos de prácticas de campo:</label>
  <?php echo form_input('creditos_campo', $result->creditos_campo); ?><br />
  <label for="grupos_campo">Grupos de prácticas de campo:</label>
  <?php echo form_input('grupos_campo', $result->grupos_campo); ?><br />  

  <?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/show/' . $result->Asignatura->titulacion_id, 'Cancelar') ?>  
<?php echo form_close(); ?>
