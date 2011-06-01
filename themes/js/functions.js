var titulaciones = {
    initialize: function() {
        $('#linknewtitulacion').click( function(event) {
            event.preventDefault();
            titulaciones.displayAdd($(this).attr('href'));
        });
    },
    //Add controller
    //Carga y muestra el formulario de add
    displayAdd: function(href) {
        var p = {};
        p['js']=1;
        $('<div id="nuevatitulacion" style="display:none"></div>').insertAfter('#listatitulaciones')
        
        $('#nuevatitulacion').load(href, p, titulaciones.loadAddCallback);
        $('#linknewtitulacion').toggle();
        $('#canceltitulacion').toggle();
    },
    //Callback de la función load de add para mostrar el formulario y añadir el evento click
    loadAddCallback: function() {
        $('#nuevatitulacion').slideDown();
        $('#canceltitulacion').click( function(event) {
            event.preventDefault();
            $('#nuevatitulacion').slideUp();
            $('#linknewtitulacion').toggle();
        });
    }
}

$(document).ready(function(){
    titulaciones.initialize();
});
