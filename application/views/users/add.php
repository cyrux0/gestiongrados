<?= form_open($action) ?>
<div class="field">
    <label for="titulacion">Titulación:</label>
    <?= form_dropdown('id_titulacion', $titulaciones) ?>
</div>
<div class="field">
    <label for="nombre">Nombre:</label>
    <?= form_input('nombre', set_value('nombre', '')) ?>
</div>
<div class="field">
    <label for="apellidos">Apellidos:</label>
    <?= form_input('apellidos', set_value('apellidos', '')) ?>
</div>
<div class="field">
    <label for="DNI">DNI:</label>
    <?= form_input('DNI', set_value('DNI', '')) ?>
</div>
<div class="field">
    <label for="email">Email (@uca.es):</label>
    <?= form_input('email', set_value('email', '')) ?>
</div>
<? if(Current_User::logged_in(0)): ?>
<div class="field">
    <label for="level">Administrador:</label>
    <?= form_radio('level', '0', set_radio('level', '0', TRUE)) ?>
    <label for="level">Planificador:</label>
    <?= form_radio('level', '1', set_radio('level', '1')) ?>
    <label for="level">Profesor:</label>
    <?= form_radio('level', '2', set_radio('level', '2')) ?>
    <label for="level">Alumno:</label>
    <?= form_radio('level', '3', set_radio('level', '3')) ?>
</div>
<? endif; ?>

<div class="actions">
    <?= form_submit('submit', 'Enviar') ?>
</div>
<?= form_close() ?>
