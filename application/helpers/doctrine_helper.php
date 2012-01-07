<?php

require_once APPPATH.'helpers/Doctrine.php';
require_once APPPATH.'config/database.php';

// Allow Doctrine to load Model classes automatically:
spl_autoload_register( array('Doctrine', 'autoload') );
$CI =& get_instance();
$CI->load->database();
// Load our database connections into Doctrine_Manager:
$dsn = 'mysql' .
'://' . $CI->db->username .
':'   . $CI->db->password .
'@'   . $CI->db->hostname .
'/'   . $CI->db->database ;

$conn = Doctrine_Manager::connection($dsn, 'default');
$conn->setCharset('utf8');

// Load the Model class and tell Doctrine where our models are located:
require_once BASEPATH.'core/Model.php';
Doctrine::loadModels(APPPATH.'models');

// Allow the use of mutators:
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);

// Set all table columns to NOT NULL and UNSIGNED (for INTs) by default:
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_DEFAULT_COLUMN_OPTIONS, array('notnull' => true, 'unsigned' => true));

// Set the default primary key to be named 'id' (4-byte INT):
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_DEFAULT_IDENTIFIER_OPTIONS, array('name' => 'id', 'type' => 'integer', 'length' => 4));

// Activate validations
Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);


