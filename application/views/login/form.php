<?= form_open('login/submit') ?>
<div class="field">
<label for="username">Usuario:</label>
<?= form_input('username', set_value('username')) ?>
</div>
<div class="field">
<label for="password">Password:</label>
<?= form_password('password') ?>
</div>
<div class="actions">
<?= form_submit('enviar', 'Enviar') ?>
</div>

<?= form_close() ?>

