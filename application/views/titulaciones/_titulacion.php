<li><span><?= anchor('titulaciones/show/' . $item->id, $item->nombre) ?></span><?= anchor('asignaturas/add_to/' . $item->id, '+'); ?></li>

<!--
      <td><?= anchor('titulaciones/show/'.$item->id, 'Ver Asignaturas') ?></td>
      <td><?= anchor('titulaciones/delete/'.$item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
      <td><?= anchor('titulaciones/edit/'.$item->id, 'Editar', ''); ?></td>
    -->