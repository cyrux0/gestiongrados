<table class="listaelems">
    <tr><th>Asignatura</th><th colspan="2">Teoría</th><th colspan="2">Laboratorio</th><th colspan="2">Problemas</th><th colspan="2">Informática</th><th colspan="2">Prácticas de campo</th></tr>
    <tr><th></th><th>Horas</th><th>Nº grupos</th><th>Horas</th><th>Nº grupos</th><th>Horas</th><th>Nº grupos</th><th>Horas</th><th>Nº grupos</th><th>Horas</th><th>Nº grupos</th></tr>
    <? foreach($salida as $planificacion): ?>
    <tr><td><?= $planificacion[0] ?></td>
        <td><?= isset($planificacion[1])? $planificacion[1][0]:'' ?></td><td><?= isset($planificacion[1])? $planificacion[1][1]:'' ?></td>
        <td><?= isset($planificacion[2])? $planificacion[2][0]:'' ?></td><td><?= isset($planificacion[2])? $planificacion[2][1]:'' ?></td>
        <td><?= isset($planificacion[3])? $planificacion[3][0]:'' ?></td><td><?= isset($planificacion[3])? $planificacion[3][1]:'' ?></td>
        <td><?= isset($planificacion[4])? $planificacion[4][0]:'' ?></td><td><?= isset($planificacion[4])? $planificacion[4][1]:'' ?></td>
        <td><?= isset($planificacion[5])? $planificacion[5][0]:'' ?></td><td><?= isset($planificacion[5])? $planificacion[5][1]:'' ?></td></tr>
    <? endforeach; ?>
</table>

