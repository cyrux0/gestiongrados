
<h1>Carga de trabajo de la asignatura <?= $asignatura->nombre ?></h1>
<div class="ficha">
<? if($carga->creditos_teoria != 0): ?>
Créditos de teoría: <?= $carga->creditos_teoria ?><br/>
Grupos de teoría: <?= $carga->grupos_teoria ?></br>
<? endif; ?>
<? if($carga->creditos_problemas != 0): ?>
Créditos de problemas: <?= $carga->creditos_problemas ?><br/>
Grupos de problemas: <?= $carga->grupos_problemas ?></br>
<? endif; ?>
<? if($carga->creditos_informatica != 0): ?>
Créditos de informática: <?= $carga->creditos_informatica ?><br/>
Grupos de informática: <?= $carga->grupos_informatica ?></br>
<? endif; ?>
<? if($carga->creditos_lab != 0): ?>
Créditos de laboratorio: <?= $carga->creditos_lab ?><br/>
Grupos de laboratorio: <?= $carga->grupos_lab ?></br>
<? endif; ?>
<? if($carga->creditos_campo != 0): ?>
Créditos de prácticas de campo: <?= $carga->creditos_campo ?><br/>
Grupos de prácticas de campo: <?= $carga->grupos_campo ?></br>
<? endif; ?>
</div>
<!-- Falta definir la vista al completo -->
