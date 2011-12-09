<? foreach($asignaturas_semanas as $num_semana => $asignaturas_guardadas): ?>

<div id="asignaturas-guardadas-<?= $num_semana ?>" class="asignaturas-guardadas-visualizacion" data-semana="<?= $num_semana ?>" style="display:none">
    <?= $asignaturas_guardadas ?>
</div>
<h3>Horario semana <?= $num_semana == $semana_tipo ? "tipo" : $num_semana ?></h3>
<div id="horario-semana-<?= $num_semana ?>" class="horario-visualizacion" data-semana="<?= $num_semana ?>" data-slot="<?= $slot_minimo?>">
    
</div>

<? endforeach;?>