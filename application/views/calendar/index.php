
--------------------
<?= form_open('calendar/create') ?>

<input type="text" name="fecha" id="fecha_original" class="datepicker" />

<input type="text" name="fecha_alt" id="fecha_alt" value="<?= $fecha_alt ?>"/>

<input type="submit" name"enviar" value="enviar" />
<?= form_close() ?>

--------------------