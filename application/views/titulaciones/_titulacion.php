<tr>
<td><?= $item->nombre ?></td>
<td><a href="<?= site_url('titulaciones/show/' . $item->id) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/index.png') ?>"/></a></td>
<td><a href="<?= site_url('titulaciones/delete/' . $item->id) ?>" class="img-anchor" onclick="javascript:return confirm('¿Está seguro?')"><img src="<?=site_url('themes/css/img/delete-icon.png') ?>"/></a></td>
<td><a href="<?= site_url('titulaciones/edit/' . $item->id) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/edit.png') ?>"/></a></td>
</tr>