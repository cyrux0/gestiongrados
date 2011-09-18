<?= form_open($action) ?>

<div class="field">
<label for="login">Login:</label>
<?= form_input('username',set_value('login', $user->username)) ?>
</div>

<div class="field">
    <label for="password">Password:</label>
    <?= form_password('password', '') ?>
</div>

<div class="field">
    <label for="password">Confirmación de password:</label>
    <?= form_password('passconf', ''); ?>
</div>

<div class="field">
    <label for="admin">Administrador:</label>
    <?= form_checkbox('admin', '1', set_value('admin', $user->admin)) ?>
</div>

<div class="field">
    <label for="planner">Planificador:</label>
    <?= form_checkbox('planner', '1', set_value('planner', $user->planner)) ?>
</div>

<div class="actions">
    <?= form_submit('submit', 'Enviar') ?>
</div>
<?= form_close() ?>
