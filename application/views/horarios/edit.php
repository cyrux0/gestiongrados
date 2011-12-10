<div>
    <? if($horario_tipo): ?>
        <? foreach(range(1, $num_semanas_teoria) as $i): ?>
            <?= anchor("horarios/edit_teoria/$horario->id/$i", "Editar semana $i", 'class="button"') ?>
        <? endforeach;?>
    <? else: ?>
        <? $tipo_id = $horario->horariotipo[0]->id; ?>
        <? foreach(range(1, $num_semanas_teoria) as $i): ?>
            <? if($horario->num_semana != $i): ?>
                
                <?= anchor("horarios/edit_teoria/$tipo_id/$i", "Editar semana $i", 'class="button"') ?>
            <? endif; ?>
        <? endforeach;?>
        <?= anchor("horarios/edit/$tipo_id/", "Editar semana tipo", 'class="button"') ?>
        <?= form_open('horarios/add_extra_slot'); ?>
        <?= form_hidden('id_horario', $horario->id) ?>
        <?= form_dropdown('asignatura', $array_asignaturas) ?>
        <div class="actions">
            <?= form_submit('submit', 'Añadir slot extra'); ?>
        </div>
        <?=form_close();?>
    <? endif;?>
</div>
<div style="clear:both;margin-bottom:10px"> </div>
<? $i = 1; ?>

<div id="tabs">
    <ul>
        <? foreach($array_asignaturas_abv as $asignatura): ?>
        <li><a href="#tabs-<?= $i ?>"><?= $asignatura ?></a></li>
        <? $i++; ?>
        <? endforeach; ?>
    </ul>
    <? $i = 1; ?>
<div id="asignaturas"> 
    
    <? foreach($asignaturas_por_asignar as $array_lineas): ?>
    <div id="tabs-<?= $i ?>">
        <? $i=$i+1; ?>
    <table class="listaelems">
    <? foreach($array_lineas as $lineahorario): ?>
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
    <? endforeach; ?>
    
</div> 
</div>

<div id="asignaturas-guardadas" style="display:none">
    <?= json_encode($asignaturas_asignadas) ?>
</div>

<div id="check-horario"></div>
<br/>
<div id="horario" data-slot="<?= $slot_minimo?>">
    
</div>
<p>
<?= form_dropdown('ocupacion', $aulastotal) . anchor("horarios/ocupacion_aula/$horario->id_curso/$horario->semestre/$horario->num_semana" , 'Ver ocupación del aula', 'id="link-ocupacion" class="button"') ?>
    <a href="<?= site_url("horarios/exportar_ocupacion/$horario->id_curso/$horario->semestre/$horario->num_semana") ?>" class="img-anchor" id="link-exportar-ocupacion"><img src="<?= site_url('themes/css/img/csv.png') ?>" /></a>
</p>
<div id="aulas">
    
</div>

<div id="delete-line"></div>
<div id="delete-url" style="display:none"><?= site_url('horarios/delete_line/') ?></div>