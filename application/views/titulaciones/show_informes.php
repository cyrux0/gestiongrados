<?= form_open('planesdocentes/informe_asignatura/' . $id_curso) ?>
<table class="listaelems">
  <tr>
    <th>ID</th><th>NOMBRE</th><th>Añadir al informe</th>
  </tr>
  <?php 
  foreach($asignaturas as $item): ?>
  <tr>
    <td><?= $item->codigo ?></td>
    <td><?= $item->nombre ?></td>
    <td><?= form_checkbox('seleccionada[]', $item->id) ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<div class ="actions">
<?= form_submit('submit', 'Mostrar informe'); ?>
</div>
<?= form_close() ?>