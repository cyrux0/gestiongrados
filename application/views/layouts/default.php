<?php echo doctype(); ?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
    <?php echo link_tag('themes/css/style.css'); ?>
    <?php echo link_tag('themes/css/ui-lightness/jquery-ui-1.8.14.custom.css'); ?>
    <?php echo link_tag('themes/js/fullcalendar-1.5.1/fullcalendar/fullcalendar.css'); ?>
    <script src="<?= site_url('themes/js/jquery-1.6.1.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery-ui-1.8.14.custom.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery.ui.datepicker-es.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/fullcalendar-1.5.1/fullcalendar/fullcalendar.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/functions.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery.form.js'); ?>" type="text/javascript" charset="utf-8"></script>
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
      <?
        $this->load->view('layouts/notice_and_alerts');
      ?>
      {yield}
    </div>
    <div id="footer">
      Web para gestión de la planificación docente
    </div>
  </body>
</html>
