<?= form_open('horarios/visualizacion_horario_profesor') ?>
<? $curso_actual = 0 ?>
<? $titulacion_actual = ""; ?>
<?= form_hidden('id_curso', $id_curso)?>

<table class="listaelems">
    
    <? foreach($asignaturas as $asignatura): ?>
        <? if($titulacion_actual != $asignatura->asignatura->Titulacion->nombre): ?>
            <? $titulacion_actual = $asignatura->asignatura->Titulacion->nombre; ?>
            <tr><th><?= $titulacion_actual ?></th><th></th></tr>
        <? endif;?>
        <? if($asignatura->asignatura->curso != $curso_actual): ?>
        <tr><th>Curso <?=$asignatura->asignatura->curso ?></th><th></th></tr>
        <? $curso_actual = $asignatura->asignatura->curso; ?>
        <? endif;?>
        <tr><td><?= $asignatura->asignatura->nombre ?></td><td><?= form_checkbox("seleccionada[{$asignatura->id_asignatura}]", 1) ?>
            </td></tr>
    <? endforeach; ?>
</table>
<?= form_submit('submit', 'Siguiente') ?>
<?= form_close() ?>
