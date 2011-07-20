<?php
//Connection Component Binding
Doctrine_Manager::getInstance() -> bindComponent('CargaGlobal', 'default');

/**
 * CargaGlobal
 *
 *
 * @property integer $id
 *
 * @author     Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 */
class CargaGlobal extends Doctrine_Record {
    public function setTableDefinition() {
        $this -> setTableName('cargasglobales');
        $this -> hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this -> hasColumn('creditos_teoria', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('grupos_teoria', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('creditos_problemas', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('grupos_problemas', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('creditos_informatica', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('grupos_informatica', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('creditos_lab', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('grupos_lab', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('creditos_campo', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('grupos_campo', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => true, ));
        $this -> hasColumn('asignatura_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false));
        $this -> hasColumn('curso_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false));
        //Totales ¿poner?, ¿como?

    }

    public function setUp() {
        $this -> hasOne('Asignatura', array('local' => 'asignatura_id', 'foreign' => 'id'));
        $this -> hasOne('Curso as curso', array('local' => 'curso_id', 'foreign' => 'id'));
        // $this->hasMany('CargaSemanal as CargasSemanales', array('local' => 'id', 'foreign' => 'global_id'));
        parent::setUp();
    }

}

/* End of file cargaglobal.php */
/* Location: ./application/models/cargaglobal.php */