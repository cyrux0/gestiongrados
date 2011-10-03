<div class="listaelems" id="lista-titulaciones-cargas">
    <? $enlace = 'titulaciones/show/'; ?>
    <? foreach($titulaciones as $item): ?>
        <? $pretags = '<li><span>';
           $posttags = '</span></li>'; ?>
        <? $this->load->view('titulaciones/_titulacion.php', array('item' => $item, 'pretags' => $pretags, 'posttags' => $posttags, 'enlace' => $enlace)) ?>
    <? endforeach; ?>
</ul>
<br/>
<div class="actions">
<?= anchor("/", "Volver a la página principal"); ?>
</div>