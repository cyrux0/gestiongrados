

<ul class="listaelems" id="listatitulaciones">
    <? $enlace = $action; ?>
    <? foreach($titulaciones as $item): ?>
        <? $pretags = '<li><span>';
           $posttags = '</span>'; 
           if(Current_User::logged_in(1)){
                $posttags .= '<span>' . anchor('asignaturas/add_to/' . $item->id, '+') . '</span>' . anchor('titulaciones/delete/' . $item->id, 'X') . '</li>';
           }
         ?>
        <? $this->load->view('titulaciones/_titulacion.php', array('item' => $item, 'pretags' => $pretags, 'posttags' => $posttags, 'enlace' => $enlace)) ?>
    <!--<li>
      <span><a href="#"><?= $item->nombre ?></a></span>
      <?= anchor('asignaturas/add_to/' . $item->id, '+'); ?>
    
    </li> -->
    <? endforeach; ?>
</ul>
<? if(Current_User::logged_in(1)): ?>
<?= anchor('titulaciones/add', 'Añadir una nueva titulación', 'id="linknewtitulacion"'); ?>
<? endif; ?>
