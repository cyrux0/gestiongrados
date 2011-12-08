<span>Selecciona un curso</span>
<table class="listaelems">
<? foreach($cursos as $curso): ?>
<tr>
<? if(isset($action)): ?>
    <td><?= anchor($action . '/' . $curso->id, date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y")) ?></td>
    <? else: ?>
        <td><? echo date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y"); ?></td>
    <? endif; ?>
     <? 
    if((uri_string() == 'cursos/index' or uri_string()=='cursos') and Current_User::logged_in())
    {
        echo "<td>";
        echo anchor('cursos/edit/' . $curso->id, 'Editar');
        echo "</td><td>";
        echo anchor('cursos/delete/' . $curso->id, 'X');
        echo "</td>";
    }
    ?>
    </tr>
<? endforeach ?>

</table>


