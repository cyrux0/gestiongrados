<ul class="listaelems" id="listatitulaciones">
    <? foreach($titulaciones as $item): ?>
        <? $this->load->view('titulaciones/create.php', array('item' => $item)) ?>
    <!--<li>
      <span><a href="#"><?= $item->nombre ?></a></span>
      <?= anchor('asignaturas/add_to/' . $item->id, '+'); ?>
    
    </li> -->
    <? endforeach; ?>
</ul>

<!--
      <td><?= anchor('titulaciones/show/'.$item->id, 'Ver Asignaturas') ?></td>
      <td><?= anchor('titulaciones/delete/'.$item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
      <td><?= anchor('titulaciones/edit/'.$item->id, 'Editar', ''); ?></td>
    -->