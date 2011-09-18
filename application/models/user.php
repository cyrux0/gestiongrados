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
        $this->hasColumn('username', 'string', 16, array(
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
        $this->hasColumn('password', 'string', 255, array(
					    'type' => 'string',
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
    
    public function setPassword($password){
        $this->_set('password', $this->encrypt($password));
    }

    public function isAuthenticated($password){
        return $this->password == $this->encrypt($password);
    }
    
    protected function encrypt($password){
        return md5($password);
    }
}/* Fin del archivo user.php */

