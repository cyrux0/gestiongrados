<table class="listaelems">
<tr><th>Asignatura</th><th>Actividad</th><th>Grupo</th><th>Horas planificadas</th><th>Horas Reales</th></tr>
<? foreach($horas as $infoasignatura): ?>
    <? foreach($infoasignatura['horas'] as $actividad => $array_horas_actividad): ?>
        <?   foreach($array_horas_actividad as $grupo => $horas_resumen): ?>
            <tr><td><?= $infoasignatura['nombre_asignatura'] ?></td><td><?= $actividad ?></td><td><?= $grupo ?></td><td><?= $horas_resumen['planificadas'] ?></td><td><?= $horas_resumen['reales'] ?></td></tr>
            <? endforeach;
       endforeach;
   endforeach; ?>
</table>