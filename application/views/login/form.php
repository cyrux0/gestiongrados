<?= form_open('login/submit') ?>
<div class="field">
<label for="username">Email:</label>
<?= form_input('email', set_value('email')) ?>
</div>
<div class="field">
<label for="password">Password:</label>
<?= form_password('password') ?>
</div>
<div class="actions">
<?= form_submit('enviar', 'Enviar') ?>
</div>

<?= form_close() ?>

