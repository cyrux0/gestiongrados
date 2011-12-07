<?php echo doctype(); ?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
    <?php echo link_tag('themes/css/style.css'); ?>
    <?php echo link_tag('themes/css/ui-lightness/jquery-ui-1.8.14.custom.css'); ?>
    <?php echo link_tag('themes/js/fullcalendar-1.5.1/fullcalendar/fullcalendar.css'); ?>
    <?php echo link_tag('themes/js/accordion-style.css'); ?>
    <?php echo link_tag('themes/js/farbtastic.css'); ?>
    <?php echo link_tag(array('href' => 'themes/css/print_style.css','media' => "print", 'rel' => 'stylesheet', 'type' => 'text/css')); ?>
    <script src="<?= site_url('themes/js/jquery-1.6.3.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery-ui-1.8.14.custom.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery.ui.datepicker-es.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/farbtastic.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/fullcalendar-1.5.1/fullcalendar/fullcalendar.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/functions.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/jquery.form.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('themes/js/accordion-plugin.js'); ?>" type="text/javascript" charset="utf-8"></script>
    <title><?php if (isset($this->page_title)) echo $this->page_title . " | "; echo 'Universidad de Cádiz'; ?></title>
  </head>
  <body>
    <div id="header">
        <div id="uca"><div id="uca2"><a id="logoUca" href="http://www.uca.es"><img alt="Universidad de Cádiz" src="<?= site_url('themes/css/img/uca.jpg') ?>" /></a></div></div>
      <!-- Cabecera con imagen y link a la página de inicio -->
      <div id="esi"><img alt="Escuela Superior de Ingenieria" src="<?= site_url('themes/css/img/esi.png') ?>" /></div>
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
          <? if(!Current_User::logged_in())
               echo anchor('#', 'Login', 'id="login-button"');
             else
               echo "Bienvenido: <em>" . Current_User::user()->nombre . "</em> " . anchor('logout', 'Logout');
          ?>  
    </div>
      
      <div id="side_bar2">
          <ul class="menu collapsible">
              <li><?= anchor('#', 'Inicio') ?></li>
              <? if(Current_User::logged_in(1)): ?>
              <li><?= anchor('#', 'Titulaciones') ?>
                  <ul class="acitem">
                      <li><?= anchor('titulaciones/index', 'Ver titulaciones') ?></li>
                          <li><?= anchor('titulaciones/add', 'Añadir titulaciones') ?></li>
                  </ul>
              </li>
              <li><?= anchor('#', 'Asignaturas') ?>
                  <ul class="acitem">
                      <li>
                          <?= anchor('titulaciones/index', 'Asignaturas') ?>
                      </li>
                  </ul>
              </li>
              <li>
                  <?= anchor('#', 'Cursos') ?>
                  <ul class="acitem">
                      <li>
                          <?= anchor('cursos/add', 'Añadir curso') ?>
                      </li>
                      <li>
                          <?= anchor('cursos/index', 'Ver cursos') ?>
                      </li>
                  </ul>
              </li>
              <li>
                  <?= anchor('#', 'Aulas') ?>
                  <ul class="acitem">
                      <li>
                          <?= anchor('aulas/add', 'Crear aulas') ?>
                          <?= anchor('aulas/index', 'Ver aulas') ?>
                      </li>
                  </ul>
                      
              </li>
              <? endif; ?>
              <? if(Current_User::logged_in(2) or Current_User::logged_in(1)): ?>
              <li><?= anchor('#', 'Planificación Docente') ?>
                  <ul class="acitem">
                      <? if(Current_User::logged_in(1)): ?>
                        <li><?= anchor('cursos/select_curso/titulaciones/index_cargas', 'Añadir Plan Docente') ?></li>
                        <li><?= anchor('planesdocentes/make_upload', 'Importar CSV') ?></li>
                      <? endif; ?>
                      <li><?= anchor('titulaciones/show_planificacion', 'Ver Planificación') ?></li>
                  </ul>
              </li>
              <? endif; ?>
              <? if(Current_User::logged_in(1)): ?>
              <li>
                  <a href="#">Calendario</a>
                  <ul class="acitem">
                      <li><?= anchor('cursos/select_curso/eventos/index', 'Ver calendario') ?></li> 
                      <li><?= anchor('cursos/select_curso/eventos/add', 'Añadir Evento') ?></li>  
                  </ul>
              </li>
              <? endif; ?>
              <? if(Current_User::logged_in(1) or Current_User::logged_in(4)): ?>
              <li>
                  <a href="#">Horarios</a>
                  <ul class="acitem">
                      <? if(Current_User::logged_in(1)): ?>
                          <li>
                              <?= anchor('horarios/select_grupo', 'Grupos y horarios') ?>
                          </li>
                          <li>
                              <?= anchor('titulaciones/show_informes', 'Informes de asignatura') ?>
                          </li>
                      <? endif; ?>
                      <? if(Current_User::logged_in(4)): ?>
                          <li>
                              <?= anchor('horarios/visualizacion_asignaturas', 'Ver horario') ?>
                          </li>
                      <? endif; ?>
                  </ul>
              </li>
              <? endif; ?>
              <? if(Current_User::logged_in()): ?>
              <li>
                  <a href="#">Usuario</a>
                  <ul class="acitem">
                      <? if(Current_User::logged_in(0)): ?>
                          <li>
                              <?= anchor('users/add', 'Añadir usuarios') ?>
                          </li>
                      <? endif; ?>
                      <li>
                          <?= anchor('users/edit', 'Cambiar contraseña/email') ?>
                      </li>
                  </ul>
              </li>
              <? endif; ?>
              <? if(Current_User::logged_in(1)): ?>  
              <li>
                  <a href="#">Configuración</a>
                  <ul class="acitem">
                      <li><?= anchor('admin/backup', 'Backup de la BD') ?></li>
                      <li><?= anchor('admin/restaurar_backup', 'Restaurar backup') ?></li>
                  </ul>
              </li>
              <? endif; ?>
          </ul>
      </div>
      <div id="login-form" title="Login">
          <fieldset>
              <?= form_open('login/submit') ?>
                  <label for="email">Email:</label>
                  <input name="email" type="text" class="text ui-widget-content ui-corner-all"/>
                  <label for="password">Password:</label>
                  <input type="password" name="password" class="text ui-widget-content ui-corner-all" />
                <?= form_close() ?>
          </fieldset>
      </div>
        <div id="main_content">
 
        <?
            $this->load->view('layouts/notice_and_alerts');
        ?>
       <div id="print-button" class="no-print">
            <a href="javascript:window.print()"><img src="<?= site_url('themes/css/img/print.png') ?>" /></a>
        </div>
            <div style="clear:both"></div>
        {yield}
      </div>
    </div>
    <div id="footer">
      Web para gestión de la planificación docente
    </div>
  </body>
</html>
