<table class="listaelems">
  <tr>
      <th>ID</th><th>NOMBRE</th><th>CRÉDITOS</th><th>EDITAR</th><th>AÑADIR PLAN DOCENTE</th><th>VER PLAN DOCENTE</th><th>BORRAR</th>
  </tr>
  <?php 
  foreach($asignaturas as $item): ?>
  <tr>
    <td><?= $item->codigo ?></td>
    <td><?= $item->nombre ?></td>
    <td><?= $item->creditos ?></td>
    
        <td><a href="<?= site_url('asignaturas/edit/' . $item->id) ?>" class="img-anchor"><img src="<?=site_url('themes/css/img/edit.png') ?>"/></a></td>
        <td><a href="<?= site_url('planesdocentes/add_carga/' . $item->id . '/'. $id_curso) ?>" class="img-anchor" ><img src="<?=site_url('themes/css/img/add.png') ?>"/></a></td>
    
    <td><a href="<?= site_url('asignaturas/show/' . $item->id . '/'. $id_curso) ?>" class="img-anchor" ><img src="<?=site_url('themes/css/img/next.png') ?>" style="width:24px;"/></a></td>
     <td><a href="<?= site_url('asignaturas/delete/' . $item->id) ?>" class="img-anchor" onclick="javascript:return confirm('¿Está seguro?')"><img style="width:24px" src="<?=site_url('themes/css/img/delete-icon.png') ?>"/></a></td>
       
  </tr>
  <?php endforeach; ?>

  
</table>


