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
    <th colspan="<?= $num_semanas_teoria +1 ?>">
        Horarios Semestre 1
    </th>
    <th colspan="<?= $num_semanas_teoria +1 ?>">
        Horarios Semestre 2
    </th>
    <th>
        Comprobación
    </th>
    <th>
        Generar Informe
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
            <td><?= anchor("horarios/add_grupo/" . $id_titulacion . "/" . $id_curso . "/" . $num_curso . "/" . $sig_grupo, "+") ?>/-</td>
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
            <td>Check</td>
            <td>Informe</td>
        </tr>
    <? endforeach; ?>
</table>