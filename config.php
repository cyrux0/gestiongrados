<?php
$config = Config::singleton();
 
$config->set('controllersFolder', 'controllers/');
$config->set('modelsFolder', 'models/');
$config->set('viewsFolder', 'views/');
 
$config->set('dbhost', 'localhost');
$config->set('dbname', 'pfc_development');
$config->set('dbuser', 'userpfc');
$config->set('dbpass', 'pfcpass');
?>