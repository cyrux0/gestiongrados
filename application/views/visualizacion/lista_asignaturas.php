<?= form_open('horarios/visualizacion_mostrar_grupos') ?>
<? $curso_actual = 1 ?>
<?= form_hidden('id_curso', $id_curso)?>
<?= form_hidden('id_titulacion', $id_titulacion) ?>
<table class="listaelems">
    <tr><th>Asignatura</th><th>Grupos</th></tr>
    <tr><th>Curso 1</th><th></th></tr>
    <? foreach($asignaturas as $asignatura): ?>
    
    <? if($asignatura['curso'] != $curso_actual): ?>
    <tr><th>Curso <?=$asignatura['curso'] ?></th><th></th></tr>
    <? $curso_actual = $asignatura['curso']; ?>
    <? endif;?>
    <tr><td><?= $asignatura['nombre'] ?> <?= form_checkbox("seleccionada[{$asignatura['id_asignatura']}]", 1) ?></td><td>
    <? foreach(range(1, $asignatura['grupos']) as $grupo): ?>
        <?= form_radio("asignatura[{$asignatura['id_asignatura']}]", $grupo) . " " . $grupo ?>
    <? endforeach; ?>
        </td></tr>
    <? endforeach; ?>
</table>
<?= form_submit('submit', 'Siguiente') ?>
<?= form_close() ?>
