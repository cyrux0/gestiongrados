<?= form_open('horarios/guardar_aulas') ?>
<?= form_hidden('id_horario', $lineas[0]->id_horario) ?>
<table class="listaelems">
    <tr><th>Asignatura</th><th>Actividad</th><th>Grupo</th><th>Aula</th></tr>
    <? foreach($lineas as $linea): ?>
        <tr><td><?= $linea->asignatura->nombre ?></td><td><?= $linea->actividad ?></td><td><?= $linea->num_grupo_actividad ?></td><td><?= form_dropdown("aula[" . $linea->id_asignatura . '/' . $linea->actividad . '/' . $linea->num_grupo_actividad . "]", $aulas) ?></td></tr>
    <? endforeach; ?>
</table>

<div class="actions">
    <?= form_submit('submit', 'Enviar') ?>
</div>
<?= form_close() ?>
