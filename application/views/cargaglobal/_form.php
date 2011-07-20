<?php echo form_open($action, '');?>
<div class="field">
    <label for="num_semanas">
        Número de semanas en el curso:
    </label>
    <?php echo form_input('num_semanas', $result -> num_semanas);?>
    <br />
</div>
<div class="field">
<table>
    <tr>
        <td>Actividad</td>
        <td>Créditos</td>
        <td>Número de grupos</td>
    </tr>
    <?php echo form_hidden('asignatura_id', $result -> asignatura_id);?>

    <tr>
        <td>
        <label for="creditos_teoria">
            Teoría:
        </label>
        </td>
        <td>
        <?php echo form_input('creditos_teoria', $result -> creditos_teoria);?>
        </td>
        <td>
        <?php echo form_input('grupos_teoria', $result -> grupos_teoria);?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="creditos_lab">
            Laboratorio:
        </label>
        </td>
        <td>
        <?php echo form_input('creditos_lab', $result -> creditos_lab);?>
        </td>
        <td>
        <?php echo form_input('grupos_lab', $result -> grupos_lab);?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="creditos_problemas">
            Problemas:
        </label>
        </td>
        <td>
        <?php echo form_input('creditos_problemas', $result -> creditos_problemas);?>
        </td>
        <td>
        <?php echo form_input('grupos_problemas', $result -> grupos_problemas);?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="creditos_informatica">
            Informática:
        </label>
        </td>
        <td>
        <?php echo form_input('creditos_informatica', $result -> creditos_informatica);?>
        </td>
        <td>
        <?php echo form_input('grupos_informatica', $result -> grupos_informatica);?>
        </td>
    </tr>
    <tr>
        <td>
        <label for="creditos_campo">
            Prácticas de campo:
        </label>
        </td>
        <td>
        <?php echo form_input('creditos_campo', $result->creditos_campo); ?>
        </td>
        <td>
        <?php echo form_input('grupos_campo', $result->grupos_campo); ?>
        </td>
    </tr>
</table>
</div>
<?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/show/' . $result->Asignatura->titulacion_id, 'Cancelar') ?>
<?php echo form_close(); ?>
