<?php echo doctype(); ?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
    <?php echo link_tag('themes/css/style.css'); ?>
    <?php echo link_tag('themes/css/ui-lightness/jquery-ui-1.8.14.custom.css'); ?>
    <?php echo link_tag('themes/js/fullcalendar-1.5.1/fullcalendar/fullcalendar.css'); ?>
    <?php echo link_tag('themes/js/accordion-style.css'); ?>
    <script src="<?= site_url('themes/js/jquery-1.6.3.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery-ui-1.8.14.custom.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery.ui.datepicker-es.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/fullcalendar-1.5.1/fullcalendar/fullcalendar.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/functions.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery.form.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/accordion-plugin.js'); ?>" type="text/javascript" charset="utf-8"></script>
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
      <!--
      <div id="side_bar">
          <h3>Titulaciones</h3>
          <div><ul>
              <li><a href="#">Añadir titulaciones</a></li>
              <li><a href="#">Ver titulaciones</a></li>
          </ul>
          </div>
          <h3>Calendario</h3>
          <div>
              <ul>
                  <li><a href="#">Añadir evento al calendario</a></li>
                  <li><a href="#">Ver calendario completo</a></li>
              </ul>
          </div>
      </div>
      -->
      <div id="userbar">
          <? if(!Current_User::loggedIn())
               echo anchor('#', 'Login', 'id="login-button"');
             else
               echo "Logged as <em>" . Current_User::user()->username . "</em> " . anchor('logout', 'Logout');
          ?>  
    </div>
      
      <div id="side_bar2">
          <ul class="menu collapsible">
              <li><?= anchor('#', 'Inicio') ?></li>
              <li><?= anchor('#', 'Titulaciones') ?>
                  <ul class="acitem">
                      <li><?= anchor('titulaciones/index', 'Ver titulaciones') ?></li>
                      <li><?= anchor('titulaciones/add', 'Añadir titulaciones') ?></li>
                  </ul>
              </li>
              <li><?= anchor('#', 'Asignaturas') ?>
                  <ul class="acitem">
                      <li>
                          <?= anchor('titulaciones/index', 'Añadir asignaturas') ?>
                      </li>
                  </ul>
              </li>
              <li>
                  <?= anchor('#', 'Cursos') ?>
                  <ul class="acitem">
                      <li>
                          <?= anchor('cursos/add', 'Añadir curso') ?>
                      </li>
                  </ul>
              </li>
              <li><?= anchor('#', 'Planificación Docente') ?>
                  <ul class="acitem">
                      <li><?= anchor('titulaciones/index_cargas', 'Añadir Cargas') ?></li>
                      <li><?= anchor('#', 'Duplicar carga') ?></li>
                  </ul>
              </li>
              <li>
                  <a href="#">Calendario</a>
                  <ul class="acitem">
                      <li><?= anchor('eventos/index', 'Ver calendario') ?></li> <!-- Falta añadir el curso actual -->
                      <li><?= anchor('eventos/add', 'Añadir Evento') ?></li> <!-- Falta añadir el curso actual -->
                  </ul>
              </li>
              <li>
                  <a href="#">Horarios</a>
                  <ul class="acitem">
                      <li>
                          <a href="#">Configurar horario</a>
                      </li>
                      <li>
                          <a href="#">Ver horarios</a>
                      </li>
                  </ul>
              </li>
              <? if(Current_User::loggedIn()): ?>
              <li>
                  <a href="#">Usuario</a>
                  <ul class="acitem">
                      <li>
                          <?= anchor('users/edit', 'Cambiar contraseña/email') ?>
                      </li>
                  </ul>
              </li>
              <? endif; ?>  
              <li>
                  <a href="#">Configuración</a>
                  <ul class="acitem">
                      <li><?= anchor('#', 'Usuarios y grupos') ?></li>
                      <li><?= anchor('#', 'Opciones/reset') ?></li>
                  </ul>
              </li>
          </ul>
      </div>
      <div id="login-form" title="Login">
          <fieldset>
              <?= form_open('login/submit') ?>
                  <label for="username">Usuario:</label>
                  <input name="username" type="text" class="text ui-widget-content ui-corner-all"/>
                  <label for="password">Password:</label>
                  <input type="password" name="password" class="text ui-widget-content ui-corner-all" />
                <?= form_close() ?>
          </fieldset>
      </div>
        <div id="main_content">
        <?
            $this->load->view('layouts/notice_and_alerts');
        ?>
        {yield}
      </div>
    </div>
    <div id="footer">
      Web para gestión de la planificación docente
    </div>
  </body>
</html>
