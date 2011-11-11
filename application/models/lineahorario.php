<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('LineasHorarios', 'default');

/**
 * Horario
 * 
 * Esta clase representa la configuración horaria de una asignatuar dentro de una instancia de un horario.
 * Es la entidad de la que se compone Horario.
 * 
 * 
 * @author Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 *
 */
class LineaHorario extends Doctrine_Record{
    
    public function setTableDefinition(){
        $this->setTableName('lineashorarios');
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this->hasColumn('id_horario', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false));
        $this->hasColumn('id_asignatura', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false));
        $this->hasColumn('hora_inicial', 'time', null, array('type' => 'time', 'primary' => false, 'notnull' => false, 'autoincrement' => false, 'unsigned' => false));
        $this->hasColumn('hora_final', 'time', null, array('type' => 'time', 'primary' => false, 'notnull' => false, 'autoincrement' => false, 'unsigned' => false));
        $this->hasColumn('dia_semana', 'integer', 1, array('notnull' => false, 'unsigned' => true, 'range' => array(0, 4)));
        $this->hasColumn('id_actividad', 'integer', null, array('type' => 'integer', 'notnull' => false));
        $this->hasColumn('num_grupo_actividad', 'integer', null, array('unsigned' => true, 'notnull' => true));
        $this->hasColumn('slot_minimo', 'float', null, array('notnull' => true, 'autoincrement' => false, 'unsigned' => true));
        $this->hasColumn('id_aula', 'integer', null, array('type' => 'integer', 'notnull' => false));
    }
    
    public function setUp(){
        parent::setUp();
        $this->hasOne('Horario as horario', array('local' => 'id_horario', 'foreign' => 'id'));
        $this->hasOne('Asignatura as asignatura', array('local' => 'id_asignatura', 'foreign' => 'id'));
        $this->hasOne('Aula as aula', array('local' => 'id_aula', 'foreign' => 'id'));
        $this->hasOne('Actividad as actividad', array('local' => 'id_actividad', 'foreign' => 'id'));
    }
}
