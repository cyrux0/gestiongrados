<table class="listaelems">
    <tr><th>Nombre</th><th>Acción</th></tr>
    <? foreach($aulas as $aula): ?>
    <tr><td><?= $aula->nombre ?></td><td><a href="<?= site_url('aulas/delete/' . $aula->id) ?>" class="delete-button"><img src="<?=site_url('themes/css/img/delete-icon.png') ?>"/></a></td></tr>
    <? endforeach; ?>
</table>

<?= anchor('aulas/add', 'Añadir un aula') ?>
