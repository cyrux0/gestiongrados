<tr>
<td><?= $item->nombre ?></td>
<td><?= anchor('titulaciones/show/'.$item->id . '/' . (isset($id_curso)?$id_curso:''), 'Ver Asignaturas') ?></td>
<td><?= anchor('titulaciones/delete/'.$item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
<td><?= anchor('titulaciones/edit/'.$item->id, 'Editar', ''); ?></td>
</tr>