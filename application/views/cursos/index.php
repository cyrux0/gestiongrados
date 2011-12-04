<ul>
<? foreach($cursos as $curso): ?>

<li><? if(isset($action)): ?>
    <?= anchor($action . '/' . $curso->id, date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y")) ?> 
    <? else: ?>
        <? echo date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y"); ?>
    <? endif; ?>
     <? 
    if((uri_string() == 'cursos/index' or uri_string()=='cursos') and Current_User::logged_in())
    {
        echo " | ";
        echo anchor('cursos/edit/' . $curso->id, 'Editar');
        echo " | ";
        echo anchor('cursos/delete/' . $curso->id, 'X');
    }
    ?>
    </li>
<? endforeach ?>

</ul>


