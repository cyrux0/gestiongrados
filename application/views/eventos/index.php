
<ul>
<? foreach($eventos as $evento): ?>
<li><?= anchor('#', $evento['fecha_inicial']) ?> | <?= anchor('eventos/delete/' . $evento['id'], 'X')?></li>
<? endforeach ?>

</ul>

<div id="calendar_eventos_index">
<?= site_url('eventos/fetch_events/' . $this->uri->segment(3)) ?>
</div>

<?= anchor('eventos/add/' . $curso_id, 'Añadir un nuevo evento') ?>

