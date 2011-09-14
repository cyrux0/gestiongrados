<?php
// Connection Component Binding
Doctrine_Manager::getInstance() -> bindComponent('Users', 'default');
/**
 * Usuarios
 *
 * 
 *
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class User extends Doctrine_Record {
    
    public function setTableDefinition() {
        $this->setTableName('users');
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this->hasColumn('nombre', 'string', 16, array(
					    'type' => 'string',
					    'length' => 18,
				        'minlength' => 4,
					    'fixed' => false,
					    'unsigned' => false,
					    'primary' => false,
		      		    'notnull' => true,
					    'autoincrement' => false,
					    'unique' => true
					    ));
        $this->hasColumn('password', 'string', 18, array(
					    'type' => 'string',
					    'length' => 18,
				        'minlength' => 6,
					    'fixed' => false,
					    'unsigned' => false,
					    'primary' => false,
		      		    'notnull' => true,
					    'autoincrement' => false,
					    ));
        $this->hasColumn('admin', 'bool', null, array('notnull' => false));
        $this->hasColumn('planner', 'bool', null, array('notnull' => false));
    }

    public function setUp() {
        parent::setUp();

    }

}/* Fin del archivo user.php */

