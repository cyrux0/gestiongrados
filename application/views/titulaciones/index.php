<table class="listaelems" id="listatitulaciones">
    <tr><th>Nombre</th><th>Ver asignaturas</th><th>Añadir asignaturas</th><th>Editar</th><th>Borrar</th></tr>
    <? $id_curso = isset($id_curso)? $id_curso: ''; ?>
    <? foreach($titulaciones as $item): ?>
    <tr>
        <td><?= $item->nombre ?></td>
            <td><a href="<?= site_url('titulaciones/show/' . $item->id . "/" . $id_curso) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/index.png') ?>"/></a></td>
            <td><a href="<?= site_url('asignaturas/add_to/' . $item->id) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/add.png') ?>"/></a></td>
<td><a href="<?= site_url('titulaciones/edit/' . $item->id) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/edit.png') ?>"/></a></td>
<td><a href="<?= site_url('titulaciones/delete/' . $item->id) ?>" class="img-anchor" onclick="javascript:return confirm('¿Está seguro?')"><img src="<?=site_url('themes/css/img/delete-icon.png') ?>"/></a></td>
    </tr><? endforeach; ?>
</table>

<?= anchor('titulaciones/add', 'Añadir una nueva titulación'); ?>

