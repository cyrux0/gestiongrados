<table class="listaelems" id="listatitulaciones">
	<tr>
		<th>ID</th><th>NOMBRE</th><th>CRÉDITOS</th><th colspan="3">Opciones</th>
	</tr>
	<?php 
	foreach($titulaciones as $item): ?>
	<tr>
	  <td><?= $item->codigo ?></td>
	  <td><?= $item->nombre ?></td>
	  <td><?= $item->creditos ?></td>
	  <td><?= anchor('titulaciones/show/'.$item->id, 'Ver Asignaturas') ?></td>
	  <td><?= anchor('titulaciones/delete/'.$item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
	  <td><?= anchor('titulaciones/edit/'.$item->id, 'Editar', ''); ?></td>
	</tr>
	<?php endforeach; ?>

	
</table>
<?= anchor('titulaciones/add', 'Añadir una nueva titulación', 'id="linknewtitulacion"'); ?>
<?= anchor('titulaciones/index', 'Cancelar', 'id="canceltitulacion" style="display:none"'); ?>

