
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
                        $('#login-form form').submit();
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

var fullcalendar;

var calendar = {
    initialize: function(){

        $('#form_add_event').hide();

        var url = $('#calendar_eventos_index').html();
        $('#calendar_eventos_index').html('');

        $.getJSON(url, function(data){
            fullcalendar = $('#calendar_eventos_index').fullCalendar({
                height: 400,
                aspect_ratio: 1,
                theme: true,
                selectable: true,
                firstDay: 1,
                events: data,
                editable: true,
                select: calendar.selectHandler,
                eventClick: calendar.eventClick
            });  
        });
    },
    
    selectHandler: function(start, end, allDay){
        startDate = new Date(start);
        endDate = new Date(end);
        var $dialogContent = $('#form_add_event');
        var startDay = startDate.getDate();
        var startMonth = startDate.getMonth()+1; 
        var startYear = startDate.getFullYear();
        
        var endDay= endDate.getDate();
        var endMonth = endDate.getMonth()+1; 
        var endYear = endDate.getFullYear();
        
        //resetForm($dialogContent); //Crear función resetForm
        var startField = $dialogContent.find('#start_date_holder').html(startDay + "-" + startMonth + "-" + startYear );
        var endField = $dialogContent.find('#end_date_holder').html(endDay + "-" + endMonth + "-" + endYear );
        $dialogContent.find("input[name='fecha_inicial']").val(startYear + "-" + startMonth + "-" + startDay);
        $dialogContent.find("input[name='fecha_final']").val(endYear + "-" + startMonth + "-" + endDay);
        $dialogContent.find("input[name='nombre_evento']").val("").removeAttr("disabled");
        $dialogContent.dialog({
            modal: true,
            title: "Nuevo evento",
            close: function(){
                $dialogContent.dialog("destroy");
                $dialogContent.hide();
                $('.alert').remove();
               // $('#calendar').fullcalendar(); //BORRAR EVENTOS NO SALVADOS
            },
            
            buttons: {
                Guardar: function(){

                    var url = $('#form_add_event form').attr("action");
                    var options = {
                        data:    {'remote': 'true', 'tipo_evento' : 'festivo'},
                        dataType: 'json',
                        success: function(data, textStatus, jqXHR){
                            if(data.success == "true"){
                                $dialogContent.dialog("close");
                                eventObject = {
                                    id: data.id,
                                    start: start,
                                    end: end,
                                    title: data.nombre_evento
                                };
                                fullcalendar.fullCalendar('renderEvent', eventObject, true);
                            }else{
                                $('#form_add_event').prepend(data.errors);
                                
                            }
                        },//Función a la que llamar para actualizar el calendario
                        url: url,
                        type: 'post'
                    }
                    
                    $('#form_add_event form').ajaxSubmit(options);
                    
                },
                
                Cancelar: function(){
                    $dialogContent.dialog("close");
                }
            }
        });
        
    },
    
    eventClick: function(calEvent, jsEvent, view){
        startDate = new Date(calEvent.start);
        endDate = new Date(calEvent.end);
        var $dialogContent = $('#form_add_event');
        var startDay = startDate.getDate();
        var startMonth = startDate.getMonth()+1; 
        var startYear = startDate.getFullYear();
        
        var endDay= endDate.getDate();
        var endMonth = endDate.getMonth()+1; 
        var endYear = endDate.getFullYear();
        
        //resetForm($dialogContent); //Crear función resetForm
        var startField = $dialogContent.find('#start_date_holder').html(startDay + "-" + startMonth + "-" + startYear );
        var endField = $dialogContent.find('#end_date_holder').html(endDay + "-" + endMonth + "-" + endYear );
        $dialogContent.find("input[name='fecha_inicial']").val(startYear + "-" + startMonth + "-" + startDay);
        $dialogContent.find("input[name='fecha_final']").val(endYear + "-" + startMonth + "-" + endDay);
        $dialogContent.find("input[name='nombre_evento']").val(calEvent.title).attr("disabled", "disabled");
        $dialogContent.dialog({
            modal: true,
            title: "Ver evento",
            close: function(){
                $dialogContent.dialog("destroy");
                $dialogContent.hide();
               // $('#calendar').fullcalendar(); //BORRAR EVENTOS NO SALVADOS
            },
            
            buttons: {
                Borrar: function(){
                    var url = $('#delete_url').html();
                    url = url + '/' + calEvent.id;
                    var options = {
                        url: url
                    };
                    
                    $.ajax(options);
                    fullcalendar.fullCalendar("removeEvents", calEvent.id);
                    $dialogContent.dialog("close");
                },
                
                Cancelar: function(){
                    $dialogContent.dialog("close");
                }
            }
        });
    }
    
}

// EN PRUEBAS
var horarios = {
    
    initialize: function(){
            $("#asignaturas div.external-event").each(function(){

                var events = eval($(this).find(".hidden-info").text());
                $(this).data('events', events);
                
                $(this).draggable({
                    zIndex: 999,
                    revert: true,
                    revertDuration: 0
                });
                
            });
            
            var savedEvents = eval($('#asignaturas-guardadas').text());
            
            for(var i in savedEvents){
                
                hora_inicial = savedEvents[i].hora_inicial.split(":");
                hora_final = savedEvents[i].hora_final.split(":");
                dia_semana = eval(savedEvents[i].dia_semana);
                savedEvents[i].start = new Date(1950, 0, 2 + dia_semana, hora_inicial[0], hora_inicial[1]);
                savedEvents[i].end = new Date(1950, 0, 2 + dia_semana, hora_final[0], hora_final[1]);
                savedEvents[i].title = savedEvents[i].nombre_asignatura;
                savedEvents[i].allDay = false;
                switch(savedEvents[i].actividad){
                    case 'teoria':
                        savedEvents[i].color = '#3366CC';
                        break;
                    case 'lab':
                        savedEvents[i].color = '#B22222';
                        break;
                    case 'problemas':
                        savedEvents[i].color = '#FFD700';
                        break;
                    case 'informatica':
                        savedEvents[i].color = '#808000';
                        break;
                    case 'campo':
                        savedEvents[i].color = '#696969';
                        break;
                    default:
                        break;
                }
            }
            
            $("#horario").fullCalendar({
                events: savedEvents,
                theme: true,
                weekends: false,
                header: {
                    left: 'title',
                    center: '',
                    right: ''
                },
                defaultView: 'agendaWeek',
                titleFormat: false,
                columnFormat: {
                    week: 'ddd'
                },
                editable: true,
                disableResizing: true,
                droppable: true,
                allDaySlot: false,
                minTime: 9,
                maxTime: 22,
                height: 400,
                aspect_ratio: 1,
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                eventDrop: function(eventCalendar, dayDelta, minuteDelta, allDay, revertFunc){
                    horarios.move(eventCalendar);
                },
                
                drop: function(date, allDay){

                    var events = $(this).data('events');
                    var originalObject = events.shift();
                    var copiedEventObject = $.extend({}, originalObject);
                    copiedEventObject.start = date;
                    var endDate = new Date(date.valueOf() + 60000 * 60 *copiedEventObject.slot_minimo);
                    copiedEventObject.end = endDate; 
                    copiedEventObject.title = copiedEventObject.nombre_asignatura;
                    copiedEventObject.allDay = false;
                    switch(copiedEventObject.actividad){
                        case 'teoria':
                            copiedEventObject.color = '#3366CC';
                            break;
                        case 'lab':
                            copiedEventObject.color = '#B22222';
                            break;
                        case 'problemas':
                            copiedEventObject.color = '#FFD700';
                            break;
                        case 'informatica':
                            copiedEventObject.color = '#808000';
                            break;
                        case 'campo':
                            copiedEventObject.color = '#696969';
                            break;
                        default:
                            break;
                    }
                    horarios.move(copiedEventObject);

                    
                    $('#horario').fullCalendar('renderEvent', copiedEventObject, true);
                    
                    if(events.length == 0){
                        $(this).remove();
                    }else{
                        $(this).find(".subject-count").text(events.length);
                    }

                    
                }
        });
        
        $('#horario').fullCalendar('gotoDate', 1950); // Mon, 2-ene-1950
        // Nos vamos a ese año para no mostrar resaltada la fecha de hoy, luego da igual por que en teoría no se guarda la fecha, solo la hora. Luego claro en el PHP habrá que parsear sólo la hora.
        // Para salvar los eventos en la BD llamamos a clientEvents y lo mandamos a una acción de un controlador.
    },
    
    move: function(eventCalendar){
        var data = { 
            id: eventCalendar.id,
            hora_inicial: eventCalendar.start.getHours(),
            minuto_inicial: eventCalendar.start.getMinutes(),
            hora_final: eventCalendar.end.getHours(),
            minuto_final: eventCalendar.end.getMinutes(),
            dia_semana: eventCalendar.start.getDay() - 1
        };

        var request = {
            url: eventCalendar.save_url,
            data: data,
            type: "POST",
        };
        
        $.ajax(request);
    }
}

$(document).ready(function(){
    titulaciones.initialize();
    cursos.initialize();
    eventos.initialize();
    login.initialize();
    cargas.initialize();
    calendar.initialize();
    horarios.initialize();
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
    
    
    

});
