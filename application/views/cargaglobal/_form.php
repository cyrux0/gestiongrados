<?php echo form_open($action,''); ?>
<label for="num_semanas">Número de semanas en el curso:</label>
<?php echo form_input('num_semanas', $result->num_semanas); ?><br />

<table>
  <tr><td>Actividad</td><td>Horas</td><td>Número de grupos</td></tr>
  <?php echo form_hidden('asignatura_id', $result->asignatura_id); ?>
  
  <tr>
    <td><label for="horas_teoria">Teoría:</label></td>
    <td><?php echo form_input('horas_teoria', $result->horas_teoria); ?></td>
    <td><?php echo form_input('grupos_teoria', $result->grupos_teoria); ?></td>
  </tr>
  
  <tr>
    <td><label for="horas_lab">Laboratorio:</label></td>
    <td><?php echo form_input('horas_lab', $result->horas_lab); ?></td>
    <td><?php echo form_input('grupos_lab', $result->grupos_lab); ?></td>
  </tr>
  
  <tr>
    <td><label for="horas_problemas">Problemas:</label></td>
    <td><?php echo form_input('horas_problemas', $result->horas_problemas); ?></td>
    <td><?php echo form_input('grupos_problemas', $result->grupos_problemas); ?></td>
  </tr>
  
  <tr>
    <td><label for="horas_informatica">Informática:</label></td>
    <td><?php echo form_input('horas_informatica', $result->horas_informatica); ?></td>
    <td><?php echo form_input('grupos_informatica', $result->grupos_informatica); ?></td>
  </tr>
  
  <tr>
    <td><label for="horas_campo">Prácticas de campo:</label></td>
    <td><?php echo form_input('horas_campo', $result->horas_campo); ?></td>
    <td><?php echo form_input('grupos_campo', $result->grupos_campo); ?></td>
  </tr>
</table>
  <?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/show/' . $result->Asignatura->titulacion_id, 'Cancelar') ?>  
<?php echo form_close(); ?>
