Selecciona una titulación: 

<table class="listaelems">
    <tr><th>Titulación</th><th>Seleccionar</th></tr>
    <? foreach($titulaciones as $titulacion): ?>
    <tr><td><?= $titulacion->nombre ?></td>
    <td><a href="<?= site_url($action . '/' . $titulacion->id) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/next.png') ?>"/></a></td>
    </tr>
    <? endforeach; ?>
</table>
