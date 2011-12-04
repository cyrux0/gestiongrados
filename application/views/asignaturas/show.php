
<h1>Carga de trabajo de la asignatura <?= $asignatura->nombre ?></h1>
<table class="listaelems">
    <tr><th>Actividad</th><th>Horas</th><th>Horas semanales</th><th>Semanas alternas</th></tr>
    <? foreach($cargas as $plan): ?>
    <tr><td><?= $plan->actividad->descripcion ?></td><td><?= $plan->horas ?></td><td><?= $plan->horas_semanales ?></td><td><?= $plan->alternas? "Sí":"No" ?></td></tr>
    <? endforeach; ?>
</table>
<!-- Falta definir la vista al completo -->
