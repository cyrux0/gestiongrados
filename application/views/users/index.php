<table class="listaelems">
    <tr><th>ID</th><th>Usuario</th><th>Editar</th><th>Borrar</th></tr>
    <? foreach($users as $user): ?>
        <tr><td><?= $user->id ?></td><td><?= $user->email ?></td><td><?= anchor("users/admin_edit/$user->id", "Editar") ?></td><td><a href="<?= site_url('users/delete/' . $user->id) ?>" class="delete-button"><img src="<?=site_url('themes/css/img/delete-icon.png') ?>"/></a></td></tr>
    <? endforeach; ?>
</table>
