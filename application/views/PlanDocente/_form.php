<?php echo form_open($action, '');?>
<table>
    <tr>
        <td>Actividad</td>
        <td>Horas</td>
        <td>Número de grupos</td>
        <td>Horas semanales</td>
        <td>Grupos en semanas alternas</td>
    </tr>
    <?php echo form_hidden('id_asignatura', $result->id_asignatura);?>
    <?= form_hidden('id_curso', $result->id_curso) ?>

    <tr>
        <td>
        <label for="horas_teoria">
            Teoría:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[1]',''); //$result->horas[teoria]);?>
        </td>
        <td>
        <?php echo form_input('grupos[1]',''); //$result->grupos[teoria]);?>
        </td>
        <td>
            <?= form_input('horas_semanales[1]','')// $result->horas_semanales[teoria]) ?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="horas_lab">
            Laboratorio:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[2]', '');// $result->horas[lab]);?>
        </td>
        <td>
        <?php echo form_input('grupos[2]','');// $result->grupos[lab]);?>
        </td>
        <td>
            <?= form_input('horas_semanales[2]','');// $result->horas_semanales[lab]) ?>
        </td>
        <td>
            <?= form_checkbox('alternas[2]', '1', FALSE) ?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="horas_problemas">
            Problemas:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[3]','');?>
        </td>
        <td>
        <?php echo form_input('grupos[3]','');?>
        </td>
        <td>
            <?= form_input('horas_semanales[3]','') ?>
        </td>
        <td>
            <?= form_checkbox('alternas[3]', 'TRUE', FALSE) ?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="horas_informatica">
            Informática:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[4]', '');?>
        </td>
        <td>
        <?php echo form_input('grupos[4]', '');?>
        </td>
        <td>
            <?= form_input('horas_semanales[4]', '') ?>
        </td>
        <td>
            <?= form_checkbox('alternas[4]', 'TRUE', FALSE) ?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="horas_campo">
            Prácticas de campo:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[5]', ''); ?>
        </td>
        <td>
        <?php echo form_input('grupos[5]', ''); ?>
        </td>
        <td>
            <?= form_input('horas_semanales[5]', '') ?>
        </td>
        <td>
            <?= form_checkbox('alternas[5]', 'TRUE', FALSE) ?>
        </td>
     
     
    </tr>
</table>
<div class="field">
    <?= form_label('Cursos compartidos:', 'compartidos') ?>
    <? foreach(range($curso_asignatura+1, $cursos_totales) as $numero_curso): ?>
        <?= form_checkbox('cursoscompartidos[][num_curso_compartido]', $numero_curso) ?> <label class="checkbox_label"><?= $numero_curso ?></label>
    <? endforeach; ?>
</div>

<div class="actions">
<?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/show/' . $result->Asignatura->titulacion_id, 'Cancelar') ?>
</div>
<?php echo form_close(); ?>
