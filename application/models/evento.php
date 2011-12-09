<?php
//Connection Component Binding
Doctrine_Manager::getInstance() -> bindComponent('Eventos', 'default');

/**
 * Evento
 *
 *
 * @property integer $id
 *
 * @author     Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 */
class Evento extends Doctrine_Record {
        
    public $tipo_evento_values = array('festivo', 'vacaciones', 'especial_laborable');
        
    protected function validate(){
        $fecha_inicial = new DateTime($this->fecha_inicial);
        $fecha_final = new DateTime($this->fecha_final);
        $inicio_semestre1 = new DateTime($this->curso->inicio_semestre1);
        $fin_semestre1 = new DateTime($this->curso->fin_semestre1);
        $inicio_semestre2 = new DateTime($this->curso->inicio_semestre2);
        $fin_semestre2 = new DateTime($this->curso->fin_semestre2);
        
        // Fecha final > fecha inicial
        if($fecha_inicial > $fecha_final){
            $this->getErrorStack()->add('fecha_inicial', 'La fecha de inicio debe ser anterior a la de finalización');
        }
        
        // Fechas comprendidas entre la duración del curso
        if( !(($fecha_inicial >= $inicio_semestre1 and $fecha_final <= $fin_semestre1) or ($fecha_inicial >= $inicio_semestre2 and $fecha_final <= $fin_semestre2)) ){
            $this->getErrorStack()->add('fecha_inicial', 'Las fechas deben estar comprendidas entre la duración del curso');
        }
    }
    
    public function setTableDefinition() {
        $this -> setTableName('eventos');
        $this -> hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this -> hasColumn('nombre_evento', 'string', 255, array('unsigned' => false, 'minlength' => 5, 'notblank' => true));
        $this -> hasColumn('tipo_evento', 'enum', null, array( 'values' => $this->tipo_evento_values, 'unsigned' => false));
        $this -> hasColumn('fecha_individual', 'bool');
        $this -> hasColumn('fecha_inicial', 'date', array('unsigned' => false));
        $this -> hasColumn('fecha_final', 'date', array('unsigned' => false));
        $this -> hasColumn('curso_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false));
    }

    public function setUp() {
        $this -> hasOne('Curso as curso', array('local' => 'curso_id', 'foreign' => 'id'));
        parent::setUp();
    }

}

/* End of file evento.php */
/* Location: ./application/models/evento.php */