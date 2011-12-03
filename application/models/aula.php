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
        $this->hasColumn('id', 'integer', null, array('type' => 'integer', 'primary' => true, 'autoincrement' => true));
        $this->hasColumn('nombre', 'string', 100, array('length' => 100, 'fixed' => false, 'unsigned' => false));
    }
    
    public function setUp(){
        parent::setUp();
        $this->hasMany('LineaHorario as lineashorario', array('local' => 'id', 'foreign' => 'id_aula'));
        $this->hasMany('Actividad as actividades', array('local' => 'id_aula', 'foreign' => 'id_actividad', 'refClass' => 'AulaActividad'));
    }
}
