<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CursoCompartido
 *
 * @author Dani
 */
class CursoCompartido extends Doctrine_Record
{
    public function setTableDefinition() {
        $this->setTableName('cursos_compartidos');
        $this->hasColumn('id_plandocente', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true));
        $this->hasColumn('num_curso_compartido', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'notnull' => true, 'primary' => true));
    }
    
    public function setUp()
    {
        $this->hasOne('PlanDocente as plandocente', array('local' => 'id_plandocente', 'foreign' => 'id'));
        parent::setUp();
    }
}

?>
