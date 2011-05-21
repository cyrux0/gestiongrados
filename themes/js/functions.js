$(function(){
    $('#linknewtitulacion').click(function(event){
	event.preventDefault();
	var p = {};
	p['js'] = 1;
	$('<div id="nuevatitulacion" style="display:none"></div>').insertAfter('#listatitulaciones');
	$('#nuevatitulacion').load($(this).attr('href'), p).ready(function(){
	    $('#nuevatitulacion').slideDown(800);
	});
	$('#linknewtitulacion').toggle();
	$('#canceltitulacion').toggle();
    });
});

$(function(){
    $('#canceltitulacion').click(function(event){
	event.preventDefault();
	$('#canceltitulacion').toggle();
	$('#nuevatitulacion').slideUp(1000);
	$('#linknewtitulacion').show();
	
    });
});