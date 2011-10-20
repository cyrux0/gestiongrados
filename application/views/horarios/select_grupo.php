<table class="listaelems">
    <tr>
    <th>
        Curso
    </th>
    <th>
        Grupos
    </th>
    <th colspan="3">
        Acciones
    </th>
    </tr>
    <? $ultimo_indice = 0; 
    ?>
    <? for($i = 0; $i < $num_cursos; $i++): ?>
    <?= form_open('horarios/edit') ?>
        <tr class="fila-grupo">
            <td><?= $i+1 ?></td>
            <? $check = 0;
               $grupos = array(); ?>
            <? while(!$check): ?>
            <?
                if(!isset($horarios[$ultimo_indice]) or $horarios[$ultimo_indice]->num_curso_titulacion != $i+1){
                    $check = 1;
                }else{
                    $grupos[$horarios[$ultimo_indice]->id] = $horarios[$ultimo_indice]->num_grupo_titulacion;  
                    $ultimo_indice++;
                }
                endwhile; ?>
                <td><?= form_dropdown('grupo', $grupos); ?></td>
                <? 
                $curso = $i+1;
                $num_grupo = count($grupos) + 1; 
                $disabled = count($grupos)? "false":"true"; ?> 
                <td><?= anchor('horarios/add_grupo/' . $id_titulacion . '/' . $id_curso . '/' . $curso . '/' . $num_grupo, 'Añadir Grupo') ?></td>
                <td><div class="actions"><?= anchor('horarios/edit/', 'Editar','class="button-grupo"') ?> <span class="span-button">Editar</span></div></td>
                <td><?= anchor('horarios/edit_teoria', 'Editar horario teoría', 'class="button-teoria"') ?> <span class="span-teoria">Editar horario teoría</span></td>
            <?= form_close(); ?>
        </tr>
    <? endfor; ?>
</table>