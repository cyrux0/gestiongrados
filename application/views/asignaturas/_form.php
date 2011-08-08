<?php echo form_open($action,''); ?>
  <?php echo form_hidden('titulacion_id', $result->titulacion_id); ?>
  <div class="field">
  	<label for="codigo">Código:</label>
  	<?php echo form_input('codigo', $result->codigo); ?><br />
  </div>
  <div class="field">
  	<label for="nombre">Nombre:</label> 
  	<?php echo form_input('nombre', $result->nombre); ?><br />
  </div>
  <div class="field">
  	<label for="creditos">Créditos:</label>
  	<?php echo form_input('creditos', $result->creditos); ?><br />
  </div>
  <div class="field">
  	<label for="materia">Materia:</label>
  	<?php echo form_input('materia', $result->materia); ?><br />
  </div>
  <div class="field">
  	<label for="departamento">Departamento:</label>
  	<?php echo form_input('departamento', $result->departamento); ?><br />
  </div>
  <div class="field">
      <label for="curso">Curso:</label>
      <?= form_input('curso', $result->curso) ?><br />
  </div>
  <div class="field">
      <label for="semestre">Semestre:</label>
      <?= form_input('semestre', $result->semestre) ?><br /><!-- Debería ser un form select -->
  </div>
  <div class="actions">
  	<?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/show/'.$result->titulacion_id, 'Cancelar') ?>
  </div>  
<?php echo form_close(); ?>
