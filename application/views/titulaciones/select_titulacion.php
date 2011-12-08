Selecciona una titulación: 
<table class="listaelems">
    <tr><th>Titulación</th></tr>
    <? foreach($titulaciones as $titulacion): ?>
    <tr><td><?= anchor($action . "/" . $titulacion->id, $titulacion->nombre) ?></td></tr>
    <? endforeach; ?>
</table>
