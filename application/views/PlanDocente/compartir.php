<?php echo form_open($action, '');?>

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