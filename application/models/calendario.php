<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Calendarios', 'default');

class Calendario extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('calendarios');
    $this->hasColumn('id', 'integer', 4, array(
                           'type' => 'integer',
                           'length' => 4,
                           'fixed' => false,
                           'unsigned' => false,
                           'primary' => true,
                           'autoincrement' => true,
                           ));
    $this->hasColumn('codigo', 'string', 4, array(
                          'type' => 'string',
                          'length' => 4,
                          'notnull' => true,
                          'unique' => true
                          ));
    }
}
