<table class="listaelems">
  <tr>
    <th>ID</th><th>NOMBRE</th><th>CRÉDITOS</th><th colspan="4">ACCIONES</th>
  </tr>
  <?php 
  foreach($asignaturas as $item): ?>
  <tr>
    <td><?= $item->codigo ?></td>
    <td><?= $item->nombre ?></td>
    <td><?= $item->creditos ?></td>
    <td><?= anchor('asignaturas/delete/' . $item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
    <td><?= anchor('asignaturas/edit/' . $item->id, 'Editar', ''); ?></td>
    <td><?= anchor('asignaturas/add_carga/' . $item->id, 'Añadir ficha'); ?></td>
    <td><?= anchor('asignaturas/show/' . $item->id . '/' , 'Ver ficha actual', 'class="linkvercarga"') ?></td>
  </tr>
  <?php endforeach; ?>

  
</table>


