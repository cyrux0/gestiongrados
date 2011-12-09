<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tipoAula
 *
 * @author Daniel I. Salazar Recio
 */
class Actividad extends Doctrine_Record{
    public function setTableDefinition(){
        $this->setTableName('actividades');
        $this->hasColumn('id', 'integer', null, array('type' => 'integer', 'primary' => true, 'autoincrement' => false));
        $this->hasColumn('descripcion', 'string', 100, array('length' => 100, 'fixed' => false, 'unsigned' => false));
        $this->hasColumn('identificador', 'string', 1, array('length' => 100, 'fixed' => false, 'unsigned' => false));
    }
    
    public function setUp(){
        parent::setUp();
        $this->hasMany('Aula as aulas', array('local' => 'id_actividad', 'foreign' => 'id_aula', 'refClass' => 'AulaActividad'));
        $this->hasMany('LineaHorario as lineashorario', array('local' => 'id', 'foreign' => 'id_actividad'));
        $this->hasMany('PlanActividad as planactividades', array('local' => 'id', 'foreign' => 'id_actividad'));
    }
}

?>
