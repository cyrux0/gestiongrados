<table class="listaelems" id="listatitulaciones">
    <tr><th>Nombre</th><th>Ver asignaturas</th><th>Añadir asignaturas</th><th>Borrar</th></tr>
    <? foreach($titulaciones as $item): ?>
    <tr><td><?= $item->nombre ?></td><td><?= anchor('titulaciones/show/'.$item->id . '/' . (isset($id_curso)?$id_curso:''), 'Ver Asignaturas') ?></td><td><?= anchor('asignaturas/add_to/' . $item->id, '+') ?></td><td><?=anchor('titulaciones/delete/' . $item->id, 'X') ?></td></tr>    
    <? endforeach; ?>
</table>

<?= anchor('titulaciones/add', 'Añadir una nueva titulación'); ?>

