<table class="listaelems">
    <tr>
    <th>
        Curso
    </th>
    <th>
        Nº Grupos
    </th>
    <th>
        Añadir/Eliminar
    </th>
    <th colspan="<?= $num_semanas_teoria +2 ?>">
        Horarios Semestre 1
    </th>
    <th>
        Exportar
    </th>
    <th colspan="<?= $num_semanas_teoria +2 ?>">
        Horarios Semestre 2
    </th>
    <th>
        Exportar
    </th>
    </tr>
    <? $ultimo_indice = 0; 
    ?>
    <? foreach($cursos as $key => $curso): ?>
        <tr class="fila-grupo">
            <? 
                $num_curso = $key + 1; 
                $sig_grupo = $curso['num_grupos'] + 1;
            ?>
            <td><?= $key + 1 ?></td>
            <td><?= $curso['num_grupos'] ?></td>
            <td><?= anchor("horarios/add_grupo/" . $id_titulacion . "/" . $id_curso . "/" . $num_curso . "/" . $sig_grupo, "+") ?>/
                <? if($curso['num_grupos'] == 0): ?>
                    -
                <? else: ?>
                    <?= anchor("horarios/delete_group/" . $id_titulacion . "/" . $id_curso . "/" . $curso['num_grupos'] . "/" . $num_curso, "-") ?>
                <? endif; ?>
            </td>
            <? for($i = 0; $i < $num_semanas_teoria; $i++): ?>
                <? if($curso['id_horario_sem1']): ?>
                    <td><?= anchor("horarios/edit_teoria/{$curso['id_horario_sem1']}/" . ($i+1), "Horario Semana " . ($i+1)) ?></td>
                <? else: ?>
                    <td>Horario Semana <?= $i+1 ?></td>
                <? endif; ?>
            <? endfor; ?>    
            <? if($curso['id_horario_sem1']): ?>
                <td><?= anchor("horarios/edit/{$curso['id_horario_sem1']}", "Horario tipo")?></td>
            <? else: ?>
                <td>Horario tipo</td>
            <? endif; ?>
            
            <? if($curso['id_horario_sem1']): ?>
                <td><?= anchor("horarios/check_grupo/{$curso['id_horario_sem1']}", "Comprobación") ?></td>
                <td><a href="<?=site_url("horarios/exportar/{$curso['id_horario_sem1']}") ?>" class="img-anchor"><img src="<?= site_url('themes/css/img/csv.png') ?>" /></a></td>
            <? else: ?>
                <td>Comprobación</td>
                <td></td>
            <? endif; ?>
            
            <? for($i = 0; $i < $num_semanas_teoria; $i++): ?>
                <? if($curso['id_horario_sem2']): ?>
                    <td><?= anchor("horarios/edit_teoria/{$curso['id_horario_sem2']}/" . ($i+1), "Horario Semana " . ($i+1)) ?></td>
                <? else: ?>
                    <td>Horario Semana <?= $i+1 ?></td>
                <? endif; ?>
            <? endfor; ?>
            <? if($curso['id_horario_sem2']): ?>
                <td><?= anchor("horarios/edit/{$curso['id_horario_sem2']}", "Horario tipo")?></td>
            <? else: ?>
                <td>Horario tipo</td>
            <? endif; ?>
            
            <? if($curso['id_horario_sem2']): ?>
                <td><?= anchor("horarios/check_grupo/{$curso['id_horario_sem2']}", "Comprobación") ?></td>
                <td><a href="<?=site_url("horarios/exportar/{$curso['id_horario_sem2']}") ?>" class="img-anchor"><img src="<?= site_url('themes/css/img/csv.png') ?>" /></a></td>
            <? else: ?>
                <td>Comprobación</td>
                <td></td>
            <? endif; ?>
                
        </tr>
    <? endforeach; ?>
</table>