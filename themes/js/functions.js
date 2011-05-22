$(function(){
    $('#linknewtitulacion').click(function(event){
	event.preventDefault();
	var p = {};
	p['js'] = 1;
	$('<div id="nuevatitulacion" style="display:none"></div>').insertAfter('#listatitulaciones');
	$('#nuevatitulacion').load($(this).attr('href'), p, function(){
	    $('#nuevatitulacion').slideDown();
	    $('#canceltitulacion').click(function(event){
	       event.preventDefault();
	       $('#nuevatitulacion').slideUp();
	       $('#linknewtitulacion').toggle();
	    });
	});
	$('#linknewtitulacion').toggle();
	$('#canceltitulacion').toggle();
    });
});
