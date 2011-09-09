
//Objeto titulaciones para las interacciones AJAX referentes a esta vista
var titulaciones = {
    //Función principal para crear los binds de los distintos eventos
    initialize: function() {
        
        titulaciones.preloadForm($('#linknewtitulacion').attr('href'));
        $('#linknewtitulacion').click( function(event) {
            event.preventDefault();
            titulaciones.displayAdd();
        });
        
        titulaciones.listadoAsigCargas();
    },
    
    preloadForm: function(href){
        var p = {};
        p['js'] = 1;
        if($('#nuevatitulacion').length == 0){
            $('<div id="nuevatitulacion" style="display:none"></div>').insertAfter('#listatitulaciones');
            $('#nuevatitulacion').load(href, p, titulaciones.loadAddCallback);
        }
    },
    
    //Add controller
    //Carga y muestra el formulario de add
    displayAdd: function() {
        $('#nuevatitulacion').slideDown();
        $('#linknewtitulacion').toggle();
    },
    
    //Callback de la función load de add para mostrar el formulario y añadir el evento click
    loadAddCallback: function() {
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
            dataType: 'json',
            success:    titulaciones.appendItem
        }
        
        $('#nuevatitulacion form').ajaxForm(options);
    },
    
    //Función para añadir el item a la tabla en el momento de hacer click en submit
    appendItem: function(response){
        $('.notice').remove();
        $('.alert').remove();
        $('#main_content').prepend(response.messages);
        if(response.success==1){
            $('#listatitulaciones').append(response.view);
            titulaciones.hideForm();
        }    
        setTimeout(function(){$(".notice").hide("slow");}, 3000);
        setTimeout(function(){$(".alert").hide("slow");}, 3000);
    },
    
    hideForm: function(){
        $('#nuevatitulacion').slideUp();
        $('#nuevatitulacion form').each(function(){
            this.reset();
        });
        $('#linknewtitulacion').toggle();
    },
    
    listadoAsigCargas: function(){
        
        $('.actrigger').click(function(event){
            event.preventDefault();
            var p={'js': 1};
            var href = $(this).children(0).attr('href'); 
            if($(this).next().hasClass('accontainer')){
                if($(this).hasClass("current")){
                    $(this).next().slideUp();
                    $(this).removeClass('current');
                }else{
                    $('.current').next().slideUp();
                    $('.current').removeClass('current');
                    $(this).next().slideDown();
                    $(this).addClass('current');
                }
            }else{
                $('.current').next().slideUp();
                $('.current').removeClass('current');
                $(this).after('<div class="accontainer" style="display:none"></div>');
                $(this).next().load(href, p, titulaciones.loadAsigCargasCallback);
            }
        });
    },
    
    loadAsigCargasCallback: function(){
        $(this).slideDown();
        $(this).prev().addClass('current');
        cargas.initialize();
        // En principio nada, quizás cargar el form modal
    }
}

//Objeto cursos para tratar añadir efectos a las vistas referentes a los cursos
var cursos = {
    initialize: function(){
        $('.datepicker_cursos').show();
        $('.datepicker_cursos').next().hide();
        this.setDatepickers();
    },
    
    setDatepickers: function(){
        $('.datepicker_cursos').each(function(){
            $(this).datepicker({
                dateFormat: "dd/mm/yy",
                altField: '#' + $(this).next().attr('id'),
                altFormat: "yy-mm-dd",
                numberOfMonths: 3
            });
            var fecha = $.datepicker.parseDate('yy-mm-dd', $(this).next().val());
            $(this).datepicker("setDate", fecha);
        });
               
    }
}


var eventos = {
    initialize: function(){
        $('.datepicker_eventos').show();
        $('.datepicker_eventos').next().hide();
        $('.datepicker_eventos').each(function(){
            $(this).datepicker({
                dateFormat: "dd/mm/yy",
                altField: '#' + $(this).next().attr('id'),
                altFormat: "yy-mm-dd",
                numberOfMonths: 3
            });
            var fecha = $.datepicker.parseDate('yy-mm-dd', $(this).next().val());
            $(this).datepicker("setDate", fecha);
        });
    }
}

var login = {
    initialize: function(){
        $('#login-form').dialog({
               autoOpen: false,
               height: 250,
               width: 280,
               modal: true,
               buttons: {
                   'Entrar': function(){
                        alert('No implementado aún');
                        $(this).dialog("close");
                   },
                   'Cancelar': function(){
                        $(this).dialog("close");
                   },
               },
               
               close: function(){
                   allFields.val("").removeClass("ui-state-error");
               }
        });
        
        $('#login-button').click(function(event){
           event.preventDefault();
           $('#login-form').dialog("open");
        });
    }
}


var cargas = {
    initialize: function(){
        cargas.verCarga();
    },
    
    verCarga: function(){
        if($('.linkvercarga').length > 0){
            $('#main_content').append('<div id="ficha_carga"></div>');
            $('#ficha_carga').dialog({
                autoOpen: false,
                height: 250,
                width: 500,
                modal: false
            });
        }
        $('.linkvercarga').click(function(event){
            event.preventDefault();
            $('#ficha_carga').load($(this).attr('href'), {'js': 1}, null);
            $('#ficha_carga').dialog("open");
        });
    }
}
$(document).ready(function(){
    titulaciones.initialize();
    cursos.initialize();
    eventos.initialize();
    login.initialize();
    cargas.initialize();
    var icons = {
        header: "ui-icon-circle-arrow-e",
        headerSelected: "ui-icon-circle-arrow-s"
    }
    
    /*
    $('#side_bar').accordion({
        icons: icons,
        collapsible: true
    });*/
    
    
    $('#side_bar h3').click(function(){
        $(this).next().slideToggle("fast");
        return false;
    }).next().hide();
    
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
    setTimeout(function(){$(".notice").hide("slow");}, 3000);

});
