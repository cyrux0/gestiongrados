<li><span><?= anchor('titulaciones/show/' . $item->id, $item->nombre) ?></span><span><?= anchor('asignaturas/add_to/' . $item->id, '+'); ?></span><?= anchor('titulaciones/delete/' . $item->id, 'X') ?></li>

<!--
      <td><?= anchor('titulaciones/show/'.$item->id, 'Ver Asignaturas') ?></td>
      <td><?= anchor('titulaciones/delete/'.$item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
      <td><?= anchor('titulaciones/edit/'.$item->id, 'Editar', ''); ?></td>
    -->