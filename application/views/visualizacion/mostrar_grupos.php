<?= form_open('horarios/visualizacion_mostrar_horario') ?>
<? $curso_actual = 1 ?>
<?= form_hidden('id_curso', $id_curso)?>
<?= form_hidden('id_titulacion', $id_titulacion) ?>
<label for="num_semana">Semana a mostrar:</label><?= form_dropdown('num_semana', $semanas) ?>
<table class="listaelems">
    <tr><th>Asignatura</th><th>Laboratorio</th><th>Problemas</th><th>Informática</th><th>Prácticas de campo</th></tr>
    <? foreach($asignaturas as $asignatura): ?>
    
    <tr>
    <td><?= $asignatura['nombre'] ?>
    <?= form_hidden("grupos_seleccionados[{$asignatura['id']}][1][]", $asignatura['grupo_teoria']) ?></td>
    <? foreach($asignatura['grupos'] as $actividad => $grupos): ?>
    <td>
        <? foreach($grupos as $num_grupo): ?>
        <?= form_checkbox("grupos_seleccionados[{$asignatura['id']}][$actividad][]", $num_grupo) . " " . $num_grupo ?>
        <? endforeach; ?>
    </td>
    <? endforeach;?>
    </tr>
    <? endforeach; ?>
</table>
<?= form_submit('submit', 'Siguiente') ?>
<?= form_close() ?>
