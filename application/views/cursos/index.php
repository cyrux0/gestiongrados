<span>Selecciona un curso</span>
<table class="listaelems">
    <tr><th>Curso</th>
    <? if((uri_string() == 'cursos/index' or uri_string()=='cursos') and Current_User::logged_in()) 
    {
        echo "<th>Editar</th><th>Borrar</th>";
    }else
        echo "<th>Seleccionar</th>"
    ?>
    </tr>
<? foreach($cursos as $curso): ?>
<tr>

        <td><? echo date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y"); ?></td>
    <? if(isset($action)): ?>
        <td><a href="<?= site_url($action . '/' . $curso->id) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/next.png') ?>"/></a></td>
    <? else: ?>
    <? endif; ?>
     <? 
    if((uri_string() == 'cursos/index' or uri_string()=='cursos') and Current_User::logged_in())
    {?>
        <td><a href="<?= site_url('cursos/edit/' . $curso->id) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/edit.png') ?>"/></a></td>
        <td>
        <a href="<?= site_url('cursos/delete/' . $curso->id) ?>" class="img-anchor" onclick="javascript:return confirm('¿Está seguro?')"><img src="<?=site_url('themes/css/img/delete-icon.png') ?>"/></a>
        </td>
    <? }
    ?>
    </tr>
<? endforeach ?>

</table>


