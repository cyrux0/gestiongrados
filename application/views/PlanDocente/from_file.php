<? if(isset($error)): ?>
<div class="errors">
    <?= $error; ?>
</div>
<? endif; ?>
<?= form_open_multipart($action) ?>
<div class="field">
    <label for="userfile">Archivo:</label><br/><br/>
    <input type="file" name="userfile" />
    <?//= form_hidden('id_asignatura', $id_asignatura) ?>
    <?//= form_hidden('id_curso', $id_curso) ?>
</div>
<div class="actions">
    <input type="submit" name="submit" value="Enviar" />
</div>
<?= form_close() ?>