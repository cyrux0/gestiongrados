<div id="asignaturas">
    <table class="listaelems">
    <? foreach($asignaturas_por_asignar as $lineahorario): ?>
        <tr>
            <td class="<?= "subject-" . $lineahorario[0]['id_actividad']; ?>">
                <? $class = "external-event ui-draggable" ?>
                <?= "<div class=\"" . $class . "\" id=\"subject-" . $lineahorario[0]['id'] . "\">" . $lineahorario[0]['nombre_asignatura'] ?>
                <?= "<span class=\"hidden-info\" style=\"display:none\">" . json_encode($lineahorario) . "</span></div>" ?>
            </td>
            <td>
                <span class="subject-count"><?= count($lineahorario) ?></span>
            </td>
            <td>
                <? $id_select = "\"select-subject-{$lineahorario[0]['id']}\""; ?>
                <?= form_dropdown('aula[]', $aulas[$lineahorario[0]['id_actividad']], null, "id=$id_select") ?>
            </td>
            <td class="td-color" style="width:205px">
                <form><input type="text" name="color[]" class="inputcolor" id="color-subject-<?= $lineahorario[0]['id'] ?>" value="#123456" /></form>
                <div class="colorpicker"></div>
            </td>
        </tr>
    <? endforeach; ?>
    </table>
</div>

<div id="asignaturas-guardadas" style="display:none">
    <?= json_encode($asignaturas_asignadas) ?>
</div>

<div id="check-horario"></div>
<br/>
<div id="horario">
    
</div>
<p>
<?= form_dropdown('ocupacion', $aulastotal) . anchor('horarios/ocupacion_aula/' . $horario->id_curso . '/', 'Ver ocupación del aula', 'id="link-ocupacion"') ?>
</p>
<div id="aulas">
    
</div>

<div id="delete-line"></div>
<div id="delete-url" style="display:none"><?= site_url('horarios/delete_line/') ?></div>