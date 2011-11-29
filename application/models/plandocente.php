<?php
//Connection Component Binding
Doctrine_Manager::getInstance() -> bindComponent('PlanDocente', 'default');

/**
 * PlanDocente
 *
 *
 * @property integer $id
 *
 * @author     Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 */
class PlanDocente extends Doctrine_Record {
    public function setTableDefinition() {
        $this->setTableName('planesdocentes');
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this->hasColumn('id_asignatura', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'notnull' => true));
        $this->hasColumn('id_curso', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'notnull' => true));       
}
    public function setUp() {
        $this->hasOne('Asignatura', array('local' => 'id_asignatura', 'foreign' => 'id'));
        $this->hasOne('Curso as curso', array('local' => 'id_curso', 'foreign' => 'id'));
        $this->hasMany('PlanActividad as planactividades', array('local' => 'id', 'foreign' => 'id_plandocente'));
        $this->hasMany('CursoCompartido as cursoscompartidos', array('local' => 'id', 'foreign' => 'id_plandocente'));
        parent::setUp();
    }

}

/* End of file PlanDocente.php */
/* Location: ./application/models/PlanDocente.php */