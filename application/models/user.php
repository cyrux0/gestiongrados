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

        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 
                        'length' => 4, 
                        'fixed' => false, 
                        'unsigned' => false, 
                        'primary' => true, 
                        'autoincrement' => true 
                        ));
        $this->hasColumn('password', 'string', 255, array(
					    'type' => 'string',
					    'fixed' => false,
					    'unsigned' => false,
					    'primary' => false,
		      		    'notnull' => true,
					    'autoincrement' => false
					    ));
        $this->hasColumn('nombre', 'string', 50, array(
					    'type' => 'string',
					    'length' => 50,
				        'minlength' => 4,
					    'fixed' => false,
					    'unsigned' => false,
					    'primary' => false,
                                            'notnull' => true,
					    'autoincrement' => false,
					    ));
        $this->hasColumn('apellidos', 'string', 50, array(
					    'type' => 'string',
					    'length' => 50,
				        'minlength' => 4,
					    'fixed' => false,
					    'unsigned' => false,
					    'primary' => false,
                                            'notnull' => true,
					    'autoincrement' => false,
					    ));
        $this->hasColumn('DNI', 'string', 9, array(
					    'type' => 'string',
					    'length' => 9,
				        'minlength' => 9,
					    'fixed' => false,
					    'unsigned' => false,
					    'primary' => false,
                                            'notnull' => false,
					    'autoincrement' => false,
                                            'unique' => true
					    ));
        $this->hasColumn('email', 'string', 30, array(
					    'type' => 'string',
					    'length' => 30,
				        'minlength' => 5,
					    'fixed' => false,
					    'unsigned' => false,
					    'primary' => false,
                                            'notnull' => true,
					    'autoincrement' => false,
                                            'unique' => true
					    ));
        $this->hasColumn('id_titulacion', 'integer', 4, array('type' => 'integer', 
                        'length' => 4, 
                        'fixed' => false, 
                        'unsigned' => false,
                        'notnull' => false
                        ));
        $this->hasColumn('level', 'integer', 4, array(
                        'notnull' => true, 
                        'default' => 3,
                        'range' => array(0, 3)
                        ));
        
    }

    public function setUp() {
        parent::setUp();
        $this->hasOne('Titulacion as titulacion', array('local' => 'id_titulacion', 'foreign' => 'id'));
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
    
    public function generate_password()
    {
        $lenght = 8;
        $pass = chr(rand(0, 1) ? ($x = rand(65, 90)) : ($x = rand(97, 122)));
        for ($i = 1; $i < $lenght; $i++) {
            $camino = rand(0, 2);
            switch ($camino) {
                case 0: $x = rand(65, 90);
                    break;
                case 1: $x = rand(97, 122);
                    break;
                case 2: $x = rand(48, 57);
                    break;
                default: $x = rand(48, 57);
                    break;
            }

            $pass = $pass . chr($x);
        }
        $this->setPassword($pass);
        return $pass;
    }
}/* Fin del archivo user.php */

