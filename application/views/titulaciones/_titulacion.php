<? if(isset($pretags))
       echo $pretags;
?>
<?= anchor($enlace . '/' . $item->id, $item->nombre) ?>
<? if(isset($posttags))
       echo $posttags;
?>
<!--
      <td><?= anchor('titulaciones/show/'.$item->id, 'Ver Asignaturas') ?></td>
      <td><?= anchor('titulaciones/delete/'.$item->id, 'Borrar', array('onclick'=>"return confirm('Estás seguro?')")); ?></td>
      <td><?= anchor('titulaciones/edit/'.$item->id, 'Editar', ''); ?></td>
    -->