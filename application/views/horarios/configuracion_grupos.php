<?= form_open('horarios/save_grupos') ?>
<table class="listaelems">
    <tr>
        <th>Curso</th>
        <th>Grupos</th>
    </tr>
    <? for($i = 0 ; $i < $titulacion->num_cursos; $i++): ?>
        <tr>
        <td><?= $i+1 ?></td>
        <td style="text-align:center"><?= form_input('num_grupos[]', '', 'style="width:3em"') ?></td>
        </tr>
    <? endfor; ?>
</table>
<?= form_close() ?>
