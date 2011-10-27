<div id="asignaturas">
    <? foreach($asignaturas_por_asignar as $lineahorario): ?>
        <? $class = "external-event ui-draggable subject-" . $lineahorario[0]['actividad']; ?>
        <?= "<div class=\"" . $class . "\" id=\"subject-" . $lineahorario[0]['id'] . "\">" . $lineahorario[0]['nombre_asignatura'] . "(<span class=\"subject-count\">" . count($lineahorario) . "</span>)"  ?>
        <?= "<span class=\"hidden-info\" style=\"display:none\">" . json_encode($lineahorario) . "</span></div>" ?>
    <? endforeach; ?>
</div>

<div id="asignaturas-guardadas" style="display:none">
    <?= json_encode($asignaturas_asignadas) ?>
</div>

<div id="check-horario"></div>
<br/>
<?= anchor('horarios/check_horario/' . $horario->id, "Hacer comprobación", 'class="button" id="check-button"'); ?>
<div id="horario">
    
</div>

<?= form_dropdown('ocupacion', $aulas) . anchor('horarios/ocupacion_aula/' . $horario->id_curso . '/', 'Ver ocupación del aula', 'id="link-ocupacion"') ?>

<div id="aulas">
    
</div>
