<?php
//Connection Component Binding
Doctrine_Manager::getInstance() -> bindComponent('PlanActividad', 'default');

/**
 * PlanActividad
 *
 *
 *
 * @author     Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 */
class PlanActividad extends Doctrine_Record {
    public function setTableDefinition() {
        $this->setTableName('planactividades');
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this->hasColumn('id_plandocente', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'notnull' => true));
        $this->hasColumn('id_actividad', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('horas', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true,  'default' => 0, 'notblank' => true));
        $this->hasColumn('grupos', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this->hasColumn('horas_semanales', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true,  'default' => 0, 'notblank' => true));
        $this->hasColumn('alternas', 'bool', null, array('type' => 'bool', 'notnull' => false)); //Poner default a false
        $this->check('horas_semanales < horas');
        
}
    public function setUp() {
        $this->hasOne('PlanDocente as plandocente', array('local' => 'id_plandocente', 'foreign' => 'id'));
        $this->hasOne('Actividad as actividad', array('local' => 'id_actividad', 'foreign' => 'id'));
        parent::setUp();
    }

}

/* End of file PlanDocente.php */
/* Location: ./application/models/PlanDocente.php */
