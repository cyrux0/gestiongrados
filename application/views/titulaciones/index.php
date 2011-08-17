

<ul class="listaelems" id="listatitulaciones">
    <? foreach($titulaciones as $item): ?>
        <? $this->load->view('titulaciones/_titulacion.php', array('item' => $item)) ?>
    <!--<li>
      <span><a href="#"><?= $item->nombre ?></a></span>
      <?= anchor('asignaturas/add_to/' . $item->id, '+'); ?>
    
    </li> -->
    <? endforeach; ?>
</ul>
<?= anchor('titulaciones/add', 'Añadir una nueva titulación', 'id="linknewtitulacion"'); ?>

