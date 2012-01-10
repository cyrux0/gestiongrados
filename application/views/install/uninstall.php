<p>
    Esto borrará todos los datos sin que se pueda volver atrás. ¿Estás seguro?
</p>
<?= form_open('install/uninstall') ?>
<?= form_submit('Si', 'Si'); ?> | <?= anchor('/', 'Cancelar'); ?>
<?= form_close(); ?>
<br />