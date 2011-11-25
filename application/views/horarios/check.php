<table class="listaelems">
<tr><th>Asignatura</th><th>Actividad</th><th>Horas planificadas</th><th>Horas Reales</th></tr>
<? $i = 0;
foreach($horas as $infoasignatura): ?>
    <? foreach($infoasignatura as $grupo): ?>
            <tr><td><?= $asignaturas[$i] ?></td><td><?= $grupo[0] ?></td><td><?= $grupo[1] ?></td><td><?= $grupo[2] ?></td></tr>
        <? endforeach;
        $i++;
   endforeach; ?>
</table>