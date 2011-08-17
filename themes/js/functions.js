
//Objeto titulaciones para las interacciones AJAX referentes a esta vista
var titulaciones = {
    //Función principal para crear los binds de los distintos eventos
    initialize: function() {
        $('#linknewtitulacion').click( function(event) {
            event.preventDefault();
            titulaciones.displayAdd($(this).attr('href'));
        });
        
        $('#linklistatitulaciones').click( function(event) {
            event.preventDefault();
            titulaciones.displayIndex($(this).attr('href'));
        });
    },
    
    
    displayIndex: function(href) {
        var p = {};
        p['js'] = 1;
        if($('#divtitulaciones').length > 0){
            $('#divtitulaciones').slideToggle();
        }else{
            $('<div id="divtitulaciones" style="display:none"></div>').appendTo('#listatitulaciones');
            $('#divtitulaciones').load(href, p, function(){
                $('#divtitulaciones').slideDown();
            });
        }
    },
    
    //Add controller
    //Carga y muestra el formulario de add
    displayAdd: function(href) {
        var p = {};
        p['js'] = 1;
        if($('#nuevatitulacion').length > 0){
            $('#nuevatitulacion').slideDown();
        }else{
            $('<div id="nuevatitulacion" style="display:none"></div>').insertAfter('#listatitulaciones');
            $('#nuevatitulacion').load(href, p, titulaciones.loadAddCallback);
        }
        $('#linknewtitulacion').toggle();
    },
    //Callback de la función load de add para mostrar el formulario y añadir el evento click
    loadAddCallback: function() {
        $('#nuevatitulacion').slideDown();
        
        $('#canceltitulacion').click( function(event) {
            event.preventDefault();
            titulaciones.hideForm();
        });
        
        titulaciones.prepareForm();
        

    },
    
    //Preparar el formulario para ser enviado por AJAX
    prepareForm: function(){
        var options = {
            data:    {'remote': 'true'},
            success:    titulaciones.appendItem
        }
        
        $('#nuevatitulacion form').ajaxForm(options);
    },
    
    //Función para añadir el item a la tabla en el momento de hacer click en submit
    appendItem: function(response){
        $('#listatitulaciones').append(response);
        titulaciones.hideForm();
    },
    
    hideForm: function(){
        $('#nuevatitulacion').slideUp();
        $('#nuevatitulacion form').each(function(){
            this.reset();
        });
        $('#linknewtitulacion').toggle();
    }
}

$(document).ready(function(){
    titulaciones.initialize();
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $('.datepicker').datepicker($.datepicker.regional['es']);
    $("#calendar").fullCalendar({
        weekends: false,
        header: {left: 'title', center: '', right: 'prev'},
        defaultView: 'agendaWeek',
        titleFormat: false,
        columnFormat: {week: 'ddd'},
        allDaySlot: false,
        minTime: 9,
        maxTime: 22,
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        events: [{title: 'prueba', start: new Date(), end: new Date(), allDay:false}]
         
    });
    
    var url = $('#calendar_eventos_index').html();
    $('#calendar_eventos_index').html('');
    $.getJSON(url, function(data){
        $('#calendar_eventos_index').fullCalendar({
            firstDay: 1,
            events: data,
            editable: true
        });
    });
    
});
