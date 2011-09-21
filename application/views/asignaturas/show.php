
<h1>Carga de trabajo de la asignatura <?= $asignatura->nombre ?></h1>
<div class="ficha">
<? if($carga->horas_teoria != 0): ?>
Horas de teoría: <?= $carga->horas_teoria ?><br/>
Grupos de teoría: <?= $carga->grupos_teoria ?></br>
<? endif; ?>
<? if($carga->horas_problemas != 0): ?>
Horas de problemas: <?= $carga->horas_problemas ?><br/>
Grupos de problemas: <?= $carga->grupos_problemas ?></br>
<? endif; ?>
<? if($carga->horas_informatica != 0): ?>
Horas de informática: <?= $carga->horas_informatica ?><br/>
Grupos de informática: <?= $carga->grupos_informatica ?></br>
<? endif; ?>
<? if($carga->horas_lab != 0): ?>
Horas de laboratorio: <?= $carga->horas_lab ?><br/>
Grupos de laboratorio: <?= $carga->grupos_lab ?></br>
<? endif; ?>
<? if($carga->horas_campo != 0): ?>
Horas de prácticas de campo: <?= $carga->horas_campo ?><br/>
Grupos de prácticas de campo: <?= $carga->grupos_campo ?></br>
<? endif; ?>
</div>
<!-- Falta definir la vista al completo -->
