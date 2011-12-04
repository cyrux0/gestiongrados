<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of aulaTipos
 *
 * @author Daniel I. Salazar Recio
 */
class AulaActividad extends Doctrine_Record {
    
    public function setTableDefinition()
    {
        $this->setTableName('aulaactividades');
        $this->hasColumn('id_actividad', 'integer', null, array(
            'primary' => true
        ));
        $this->hasColumn('id_aula', 'integer', null, array(
            'primary' => true
        ));
    }
}

?>
