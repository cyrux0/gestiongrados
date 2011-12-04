<table class="listaelems">
    <tr><th>Asignatura</th><th>Actividad</th><th>Horas planificadas</th><th>Horas Reales</th><th>Diferencia</th></tr>
<? $i = 0;
foreach($horas as $infoasignatura): ?>
    <? foreach($infoasignatura as $grupo): ?>
    <? if($grupo[2]-$grupo[1] === 0){
        $class_comprobacion = "comprobacion-ok";
    }else if($grupo[2] - $grupo[1] < 0){
        $class_comprobacion = "comprobacion-error";
    }else{
        $class_comprobacion = "comprobacion-warning";
    }
    ?>
            <tr class="<?= $class_comprobacion ?>"><td><?= $asignaturas[$i] ?></td><td><?= $grupo[0] ?></td><td><?= $grupo[1] ?></td><td><?= $grupo[2] ?></td>
                <td><?= $grupo[2] - $grupo[1] ?></td></tr>
        <? endforeach;
        $i++;
   endforeach; ?>
</table>