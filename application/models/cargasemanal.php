<?php
//Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CargaSemanal', 'default');

/**
 * CargaSemanal
 *
 * 
 * @property integer $id
 *
 * @author     Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 */
class CargaSemanal extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('cargas_semanales');
    $this->hasColumn('id', 'integer', 4, array(
					       'type' => 'integer',
					       'length' => 4,
					       'fixed' => false,
					       'unsigned' => false,
					       'primary' => true,
					       'autoincrement' => true,
					       ));
    $this->hasColumn('num_semana', 'integer', 4, array(
						       'type' => 'integer',
						       'length' => 4,
						       'fixed' => false,
						       'unsigned' => true,
						       ));
    $this->hasColumn('horas_teoria', 'integer', 4, array(
							 'type' => 'integer',
							 'length' => 4,
							 'fixed' => false,
							 'unsigned' => true,
							 ));
    $this->hasColumn('horas_problemas', 'integer', 4, array(
							 'type' => 'integer',
							 'length' => 4,
							 'fixed' => false,
							 'unsigned' => true,
							 ));
    $this->hasColumn('horas_informatica', 'integer', 4, array(
							 'type' => 'integer',
							 'length' => 4,
							 'fixed' => false,
							 'unsigned' => true,
							 ));
    $this->hasColumn('horas_lab', 'integer', 4, array(
							 'type' => 'integer',
							 'length' => 4,
							 'fixed' => false,
							 'unsigned' => true,
							 ));
    $this->hasColumn('horas_campo', 'integer', 4, array(
							 'type' => 'integer',
							 'length' => 4,
							 'fixed' => false,
							 'unsigned' => true,
							 ));

    $this->hasColumn('entrega_trabajo', 'boolean');

    $this->hasColumn('examen', 'boolean');

    $this->hasColumn('cargaglobal_id', 'integer', 4, array(
							  'type' => 'integer',
							  'length' => 4,
							  'fixed' => false,
							  'unsigned' => false));



  }
  
  public function setUp()
  {
    $this->hasOne('CargaGlobal as cargaglobal', array('local' => 'cargaglobal_id', 'foreign' => 'id'));
    // $this->hasMany('CargaSemanal as CargasSemanales', array('local' => 'id', 'foreign' => 'global_id'));
    parent::setUp();
  }

}
