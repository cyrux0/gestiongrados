<table class="listaelems">
  <tr>
    <th>ID</th><th>NOMBRE</th><th>CRÉDITOS</th><th colspan="3">ACCIONES</th>
  </tr>
  <?php 
  foreach($asignaturas as $item): ?>
  <tr>
    <td><?= $item->codigo ?></td>
    <td><?= $item->nombre ?></td>
    <td><?= $item->creditos ?></td>
    <? if(Current_User::logged_in(1)): ?>
        <td><?= anchor('asignaturas/delete/' . $item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
        <td><?= anchor('asignaturas/edit/' . $item->id, 'Editar', ''); ?></td>
    <? endif; ?>
    <? if(Current_User::logged_in(2) and $id_curso): ?>
        <td><?= anchor('asignaturas/add_carga/' . $item->id . '/' . $id_curso, 'Añadir ficha'); ?></td>
    <? endif; ?>
    <td><?= anchor('asignaturas/show/' . $item->id . '/' . $id_curso , 'Ver ficha actual', 'class="linkvercarga"') ?></td>
  </tr>
  <?php endforeach; ?>

  
</table>


