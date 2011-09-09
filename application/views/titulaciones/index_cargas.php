<div class="listaelems" id="lista-titulaciones-cargas">
    <? $enlace = 'titulaciones/show/'; ?>
    <? foreach($titulaciones as $item): ?>
        <? $pretags = '<h2 class="actrigger">';
           $posttags = '</h2>';
         ?>
        <? $this->load->view('titulaciones/_titulacion.php', array('item' => $item, 'pretags' => $pretags, 'posttags' => $posttags, 'enlace' => $enlace)) ?>
    <? endforeach; ?>
</ul>

Volver a la página principal