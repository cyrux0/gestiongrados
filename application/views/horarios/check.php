<table class="listaelems">
<tr><th>Asignatura</th><th>Actividad</th><th>Grupo</th><th>Horas planificadas</th><th>Horas Reales</th></tr>
<? foreach($horas as $nombre => $valores): ?>
<?   foreach($valores as $actividad => $grupos): ?>
<?     foreach($grupos as $num_grupo => $horas_calculadas): ?>
         <tr><td><?= $nombre ?></td><td><?= $actividad ?></td><td><?= $num_grupo ?></td><td>X</td><td><?= $horas_calculadas ?></td></tr>
    <? endforeach;
     endforeach;
   endforeach; ?>
</table>