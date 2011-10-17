<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Aulas', 'default');

/**
 * Aula
 * 
 * Esta clase representa un aula de la facultad, que estará asignada a multiples líneas de horario
 * para poder mostrar la ocupación.
 * 
 * @author Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 *
 */
class Aula extends Doctrine_Record{
    
    public function setTableDefinition(){
        $this->setTableName('aulas');
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this->hasColumn('nombre', 'string', 100, array('length' => 100, 'fixed' => false, 'unsigned' => false));
//        $this->hasColumn('id_horario_tipo', 'integer', 4, array('type' => 'integer', 'length' => 4, 'notnull' => false));
  //      $this->hasColumn('id_horario_teoria', 'integer', 4, array('type' => 'integer', 'length' => 4, 'notnull' => false));
    }
    
    public function setUp(){
        parent::setUp();
        $this->hasMany('LineaHorario as lineashorario', array('local' => 'id', 'foreign' => 'id_horario'));
    //    $this->hasOne('Horario as horariotipo', array('local' => 'id_horario_tipo', 'foreign' => 'id'));
      //  $this->hasOne('Horario as horarioteoria', array('local' => 'id_horario_teoria', 'foreign' => 'id'));
    }
}
