<?php echo doctype(); ?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
    <?php echo link_tag('themes/css/style.css'); ?>
    
    <script src="<?= site_url('themes/js/jquery-1.6.1.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/functions.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <title><?php if (isset($page_title)) echo $page_title; else echo 'El título de la web'; ?></title>
  </head>
  <body>
    <div id="header">
      <!-- Cabecera con imagen y link a la página de inicio -->
      <a id="logoUca" href="http://www.uca.es"><img alt="Universidad de Cádiz" src="<?= site_url('themes/img/logoEmpresa.gif') ?>" /></a>
      <div id="navegacionUca">
      	<ul id="enlacesUca">
      		<li>Inicio</li>
      		<li>Intranet</li>
      		<li>Imprimir</li>
      		<li>Mapa web</li>
      	</ul>
      </div>
      <!--<div id="user_bar">-->
	<!-- Aquí irían los links del panel de usuario -->
     <!-- </div>-->
    </div>
    <div id="main">
      <!-- Aquí irían los notice y los alert -->
      {yield}
    </div>
    <div id="footer">
      Web para gestión de la planificación docente
    </div>
  </body>
</html>
