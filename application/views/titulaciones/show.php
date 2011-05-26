<h1>Asignaturas de <?php echo $titulacion->nombre; ?></h1>
<table class="listaelems">
  <tr>
    <th>ID</th><th>NOMBRE</th><th>CRÉDITOS</th><th colspan="2">ACCIONES</th>
  </tr>
  <?php 
  foreach($asignaturas as $item): ?>
  <tr>
    <td><?= $item->codigo ?></td>
    <td><?= $item->nombre ?></td>
    <td><?= $item->creditos ?></td>
    <td><?= anchor('asignaturas/delete/'.$item->id, 'Borrar', array('onClick'=>"return confirm('Estás seguro?')")); ?></td>
    <td><?= anchor('asignaturas/edit/'.$item->id, 'Editar', ''); ?></td>
  </tr>
  <?php endforeach; ?>

  
</table>
<?= anchor('asignaturas/add_to/'.$titulacion->id, 'Añadir una nueva asignatura')?> | <?= anchor('titulaciones/index', 'Volver') ?>

