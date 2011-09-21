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
        $this -> setTableName('planesdocentes');
        $this -> hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this -> hasColumn('horas_teoria', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('grupos_teoria', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('horas_problemas', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true,  'default' => 0, 'notblank' => true));
        $this -> hasColumn('grupos_problemas', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('horas_informatica', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('grupos_informatica', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('horas_lab', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('grupos_lab', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('horas_campo', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('grupos_campo', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('horas_semanales_teoria', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('horas_semanales_problemas', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true,  'default' => 0, 'notblank' => true));
        $this -> hasColumn('alternas_problemas', 'bool', null, array('type' => 'bool', 'notnull' => false)); //Poner default a false
        $this -> hasColumn('horas_semanales_informatica', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('alternas_informatica', 'bool', null, array('type' => 'bool', 'notnull' => false));
        $this -> hasColumn('horas_semanales_lab', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('alternas_lab', 'bool', null, array('type' => 'bool', 'notnull' => false));
        $this -> hasColumn('horas_semanales_campo', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, 'default' => 0, 'notblank' => true));
        $this -> hasColumn('alternas_campo', 'bool', null, array('type' => 'bool', 'notnull' => false));
        $this -> hasColumn('asignatura_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'notnull' => true));
        $this -> hasColumn('curso_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'notnull' => true));
        //Totales ¿poner?, ¿como?
        $this->check('horas_semanales_teoria > horas_teoria');
        $this->check('horas_semanales_problemas > horas_problemas');
        $this->check('horas_semanales_informatica > horas_informatica');
        $this->check('horas_semanales_lab > horas_lab');
        $this->check('horas_semanales_campo > horas_campo');
        
}
    public function setUp() {
        $this -> hasOne('Asignatura', array('local' => 'asignatura_id', 'foreign' => 'id'));
        $this -> hasOne('Curso as curso', array('local' => 'curso_id', 'foreign' => 'id'));
        // $this->hasMany('CargaSemanal as CargasSemanales', array('local' => 'id', 'foreign' => 'global_id'));
        parent::setUp();
    }

}

/* End of file PlanDocente.php */
/* Location: ./application/models/PlanDocente.php */