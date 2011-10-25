<?= form_open('horarios/guardar_aulas') ?>
<table class="listaelems">
    <tr><th>Asignatura</th><th>Actividad</th><th>Grupo</th><th>Aula</th></tr>
    <? foreach($lineas as $linea): ?>
        <tr><td><?= $linea->nombre ?></td><td><?= $linea->actividad ?></td><td><?= $linea->num_grupo_actividad ?></td><td><?= form_dropdown("aula[" . $linea->asignatura . $linea->actividad . $linea->num_grupo_actividad . "]", $aulas) ?></td></tr>
    <? endforeach; ?>
</table>
<?= form_close() ?>
