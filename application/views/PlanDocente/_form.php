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
        <?php echo form_input('horas[teoria]',''); //$result->horas[teoria]);?>
        </td>
        <td>
        <?php echo form_input('grupos[teoria]',''); //$result->grupos[teoria]);?>
        </td>
        <td>
            <?= form_input('horas_semanales[teoria]','')// $result->horas_semanales[teoria]) ?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="horas_lab">
            Laboratorio:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[lab]', '');// $result->horas[lab]);?>
        </td>
        <td>
        <?php echo form_input('grupos[lab]','');// $result->grupos[lab]);?>
        </td>
        <td>
            <?= form_input('horas_semanales[lab]','');// $result->horas_semanales[lab]) ?>
        </td>
        <td>
            <?= form_checkbox('alternas[lab]', '1', FALSE) ?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="horas_problemas">
            Problemas:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[problemas]','');?>
        </td>
        <td>
        <?php echo form_input('grupos[problemas]','');?>
        </td>
        <td>
            <?= form_input('horas_semanales[problemas]','') ?>
        </td>
        <td>
            <?= form_checkbox('alternas[problemas]', 'TRUE', FALSE) ?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="horas_informatica">
            Informática:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[informatica]', '');?>
        </td>
        <td>
        <?php echo form_input('grupos[informatica]', '');?>
        </td>
        <td>
            <?= form_input('horas_semanales[informatica]', '') ?>
        </td>
        <td>
            <?= form_checkbox('alternas[informatica]', 'TRUE', FALSE) ?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="horas_campo">
            Prácticas de campo:
        </label>
        </td>
        <td>
        <?php echo form_input('horas[campo]', ''); ?>
        </td>
        <td>
        <?php echo form_input('grupos[campo]', ''); ?>
        </td>
        <td>
            <?= form_input('horas_semanales[campo]', '') ?>
        </td>
        <td>
            <?= form_checkbox('alternas[campo]', 'TRUE', FALSE) ?>
        </td>
     
     
    </tr>
</table>
<div class="actions">
<?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/show/' . $result->Asignatura->titulacion_id, 'Cancelar') ?>
</div>
<?php echo form_close(); ?>
