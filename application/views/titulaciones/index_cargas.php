<table class="listaelems" id="lista-titulaciones-cargas">
    <? $enlace = 'titulaciones/show/'; ?>
    <? foreach($titulaciones as $item): ?>
        <? $pretags = '<tr><td>';
           $posttags = '</td></tr>'; ?>
        <? $this->load->view('titulaciones/_titulacion.php', array('item' => $item, 'pretags' => $pretags, 'posttags' => $posttags, 'enlace' => $enlace, 'id_curso' => $id_curso)) ?>
    <? endforeach; ?>
</table>
    <br/>
<div class="actions">
<?= anchor("/", "Volver a la página principal"); ?>
</div>