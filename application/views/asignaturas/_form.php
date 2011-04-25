<?php echo form_open($action,''); ?>
  <?php echo form_hidden('titulacion', $result->titulacion_id); ?>
  <label for="codigo">Código:</label>
  <?php echo form_input('codigo', $result->codigo); ?><br />
  <label for="nombre">Nombre:</label> 
  <?php echo form_input('nombre', $result->nombre); ?><br />
  <label for="creditos">Créditos:</label>
  <?php echo form_input('creditos', $result->creditos); ?><br />
  <label for="materia">Materia:</label>
  <?php echo form_input('materia', $result->materia); ?><br />
  <label for="departamento">Departamento:</label>
  <?php echo form_input('departamento', $result->departamento); ?><br />
  <label for="horas_presen">Horas Presenciales:</label>
  <?php echo form_input('horas_presen', $result->horas_presen); ?><br />
  <label for="horas_no_presen">Horas No Presenciales:</label>
  <?php echo form_input('horas_no_presen', $result->horas_no_presen); ?><br />
  <?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/show/'.$result->titulacion_id, 'Cancelar') ?>  
<?php echo form_close(); ?>
