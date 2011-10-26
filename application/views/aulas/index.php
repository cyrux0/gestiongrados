<table class="listaelems">
    <tr><th>Nombre</th><th>Acción</th></tr>
    <? foreach($aulas as $aula): ?>
        <tr><td><?= $aula->nombre ?></td><td><?= anchor('aulas/delete/' . $aula->id, 'Borrar') ?></td></tr>
    <? endforeach; ?>
</table>

<?= anchor('aulas/add', 'Añadir un aula') ?>
