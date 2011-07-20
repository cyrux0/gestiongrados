<ul class="listaelems" id="listatitulaciones">
    <?php 
    foreach($titulaciones as $item): ?>
    <li>
      <span><a href="#"><?= $item->nombre ?></a></span>
      <?= anchor('asignaturas/add_to/' . $item->id, '+'); ?>
    <!--
      <td><?= anchor('titulaciones/show/'.$item->id, 'Ver Asignaturas') ?></td>
      <td><?= anchor('titulaciones/delete/'.$item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
      <td><?= anchor('titulaciones/edit/'.$item->id, 'Editar', ''); ?></td>
    -->
    </li>
    <?php endforeach; ?>
</ul>
