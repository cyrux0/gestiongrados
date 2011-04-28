<?php
//Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CargaGlobal', 'default');

/**
 * CargaGlobal
 *
 * 
 * @property integer $id
 *
 * @author     Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 */
class CargaGlobal extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('cargasglobales');
    $this->hasColumn('id', 'integer', 4, array(
					       'type' => 'integer',
					       'length' => 4,
					       'fixed' => false,
					       'unsigned' => false,
					       'primary' => true,
					       'autoincrement' => true,
					       ));
  }
  
  public function setUp()
  {
    parent::setUp();
  }

}
