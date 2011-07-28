<ul>
<? foreach($eventos as $evento): ?>
<li><?= anchor('#', $evento['fecha_inicial']) ?> | <?= anchor('eventos/delete/' . $evento['id'], 'X')?></li>
<? endforeach ?>

</ul>

<?= anchor('eventos/add/' . $curso_id, 'Añadir un nuevo evento') ?>

