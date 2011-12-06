<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Cursos', 'default');

/**
 * Titulacion
 * 
 * 
 * 
 * @property integer $id
 * @property string $codigo
 * @property string $nombre
 * @property integer $creditos
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Daniel Ignacio Salazar Recio <danielsalazarrecio@gmail.com>
 */
class Curso extends Doctrine_Record {

    protected function validate() {
        
        $inicio_semestre1 = new DateTime($this->inicio_semestre1);
        $inicio_semestre2 = new DateTime($this->inicio_semestre2);
        $fin_semestre1 = new DateTime($this->fin_semestre1);
        $fin_semestre2 = new DateTime($this->fin_semestre2);
        $inicio_examenes_enero = new DateTime($this->inicio_examenes_enero);
        $fin_examenes_enero = new DateTime($this->fin_examenes_enero);
        $inicio_examenes_junio = new DateTime($this->inicio_examenes_junio);
        $fin_examenes_junio = new DateTime($this->fin_examenes_junio);
        $inicio_examenes_sept = new DateTime($this->inicio_examenes_sept);
        $fin_examenes_sept = new DateTime($this->fin_examenes_sept);
        
        
        // Inicio semestre 1 y 2 deben ser anteriores a fin semestre 1 y 2
        if($inicio_semestre1 >= $fin_semestre1){
            $this->getErrorStack()->add('inicio_semestre1', 'La fecha de inicio del primer semestre es posterior a la de finalización');
        }
        
        if($inicio_semestre2 >= $fin_semestre2){
            $this->getErrorStack()->add('inicio_semestre2', 'La fecha de inicio del segundo semestre es posterior a la de finalización');
        }
        
        // La fecha de finalización del primer semestre debe ser anterior a la de inicio del segundo
        if($fin_semestre1 >= $inicio_semestre2){
            $this->getErrorStack()->add('fin_semestre1', 'La fecha de finalización del primer semestre debe ser anterior a la de inicio del segundo');
        }
        
        // Lo mismo para todos los rangos de fechas
        // La fecha de finalización de los éxamenes de enero debe ser posterior a la de inicio
        if($inicio_examenes_enero >= $fin_examenes_enero){
            $this->getErrorStack()->add('fin_examenes_enero', 'La fecha de finalización de los éxamenes de enero debe ser posterior a la de inicio');
        }
        
        if($inicio_examenes_junio >= $fin_examenes_junio){
            $this->getErrorStack()->add('fin_examenes_junio', 'La fecha de finalización de los éxamenes de junio debe ser posterior a la de inicio');
        }
        
        if($inicio_examenes_sept >= $fin_examenes_sept){
            $this->getErrorStack()->add('fin_examenes_sept', 'La fecha de finalización de los éxamenes de septiembre debe ser posterior a la de inicio');
        }

        // Años consecutivos
        if(intval($inicio_semestre1->format('Y'))+1 != intval($fin_semestre2->format('Y'))){
            $this->getErrorStack()->add('fin_semestre2', 'El año de finalización del curso debe de ser el siguiente al del inicio');
        }
        
        
        // Fechas de exámenes entre inicio y fin de curso?, o más mejor, fecha de exámenes en el mismo año del segundo cuatrimestre
        
    }

    public function setTableDefinition() {
        $this -> setTableName('cursos');
        $this -> hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => true, 'autoincrement' => true, ));
        $this -> hasColumn('num_semanas_teoria', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false, 'notblank' => true));
        $this -> hasColumn('num_semanas_semestre1', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false, 'notblank' => true, 'default' => 0));
        $this -> hasColumn('num_semanas_semestre2', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false, 'notblank' => true, 'default' => 0));
        $this -> hasColumn('horas_por_credito', 'integer', 4, array('type' => 'integer', 'length' => 4, 'fixed' => false, 'unsigned' => false, 'primary' => false, 'notnull' => true, 'notblank' => true, 'autoincrement' => false, ));
        //Esto habría que cambiarlo, no me gusta en integer
        $this -> hasColumn('slot_minimo', 'integer', null, array('type' => 'integer', 'primary' => false, 'notnull' => true, 'autoincrement' => false, 'default' => '30', 'unsigned' => false));
        $this -> hasColumn('hora_inicial', 'time', null, array('type' => 'time', 'primary' => false, 'notnull' => true, 'autoincrement' => false, 'default' => '09:00', 'unsigned' => false));
        $this -> hasColumn('hora_final', 'time', null, array('type' => 'time', 'primary' => false, 'notnull' => true, 'autoincrement' => false, 'default' => '22:00', 'unsigned' => false));
        $this -> hasColumn('inicio_semestre1', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('fin_semestre1', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('inicio_semestre2', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('fin_semestre2', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('inicio_examenes_enero', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('fin_examenes_enero', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('inicio_examenes_junio', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('fin_examenes_junio', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('inicio_examenes_sept', 'date', null, array('notblank', 'unsigned' => false));
        $this -> hasColumn('fin_examenes_sept', 'date', null, array('notblank', 'unsigned' => false));
    }

    public function setUp() {
        parent::setUp();
        $this -> hasMany('PlanDocente as planesdocentes', array('local' => 'id', 'foreign' => 'id_curso', 'onDelete' => 'CASCADE'));
        $this -> hasMany('Evento as eventos', array('local' => 'id', 'foreign' => 'curso_id', 'onDelete' => 'CASCADE'));
    }

    /**
     * Método que devuelve la información en forma de matriz de la asignación de asignaturas en un horario o aula.
     * 
     * @param string $filtro Atributo por el que se filtrará, debería ser id_horario o id_aula
     * @param integer $valor_filtro Valor que tomará el atributo anterior
     * @param string $semestre Semestre para el que se quiere obtener la matriz
     * @param integer $num_semana Semana para la que se quiere obtener la matriz
     * @return array Array con las horas colocadas en su sitio 
     */
    public function getMatrizHorario($filtro, $valor_filtro, $semestre, $num_semana)
    {
        $hora_inicial = $this->hora_inicial;
        $hora_final = $this->hora_final;
        $slot_minimo_minutos = $this->slot_minimo * 60;
        
        $hora_inicial_date = date_create_from_format("H:i:s", $hora_inicial);
        $hora_final_date = date_create_from_format("H:i:s", $hora_final);
        $interval = new DateInterval('PT30M');

        for(; $hora_inicial_date->getTimestamp() <= $hora_final_date->getTimestamp(); $hora_inicial_date->add($interval))
        {
            $matriz_horario[$hora_inicial_date->format("H:i")] = array('', '', '', '', '');
        }
        
        foreach(range(0, 4) as $dia_semana)
        {
            $lineas = Doctrine_Query::create()
                    ->select('l.*, a.abreviatura, c.identificador, c.id')
                    ->from('LineaHorario l')
                    ->innerJoin('l.asignatura a')
                    ->innerJoin('l.actividad c')
                    ->innerJoin('l.horario h')
                    ->where("l.$filtro = ?", array($valor_filtro))
                    ->andWhere('h.id_curso = ?', array($this->id))
                    ->andWhere('l.dia_semana = ?', array($dia_semana))
                    ->andWhere('l.hora_inicial IS NOT NULL')
                    ->andWhere('h.num_semana = ?', array($num_semana))
                    ->andWhere('h.semestre = ?', array($semestre))
                    ->execute();

            foreach($lineas as $linea)
            {
                $hora_inicial_linea = date_create_from_format('H:i:s', $linea->hora_inicial);
                $hora_final_linea = date_create_from_format('H:i:s', $linea->hora_final);
                $slot_linea = $linea->slot_minimo*60;
                $alternas = Doctrine_Query::create()
                        ->select('l.alternas, p.id')
                        ->from('PlanActividad l')
                        ->innerJoin('l.plandocente p')
                        ->where('p.id_asignatura = ?', array($linea->id_asignatura))
                        ->andWhere('p.id_curso = ?', $this->id)
                        ->andWhere('l.id_actividad = ?', array($linea->actividad->id))
                        ->execute();
                
                if($alternas[0]->alternas)
                    $nombre = $linea->asignatura->abreviatura . " " . $linea->actividad->identificador . ($linea->num_grupo_actividad*2-1) . "-" . $linea->actividad->identificador . ($linea->num_grupo_actividad*2);
                else
                    $nombre = $linea->asignatura->abreviatura . " " . $linea->actividad->identificador . $linea->num_grupo_actividad;
                
                for(; $hora_inicial_linea->getTimestamp() < $hora_final_linea->getTimestamp(); $hora_inicial_linea->add($interval))
                {
                    $matriz_horario[$hora_inicial_linea->format("H:i")][$dia_semana] .= $matriz_horario[$hora_inicial_linea->format("H:i")][$dia_semana] != ''? '|':'';
                    $matriz_horario[$hora_inicial_linea->format("H:i")][$dia_semana] .= $nombre;
                }
            }
        }
        // Extraemos las claves del array (serían las horas del horario)
        $horas = array_keys($matriz_horario);
        // Trasponemos
        $valores = array_values($matriz_horario);
        array_unshift($valores, array('L', 'M', 'X', 'J', 'V'));
        $horario_traspuesto = call_user_func_array('array_map',array_merge(array(NULL),$valores));
        // Insertamos un blanco inicial en las horas y las insertamos de nuevo
        array_unshift($horas, '');
        array_unshift($horario_traspuesto, $horas);
        // Trasponemos de nuevo y tendremos la matriz resultado deseada lista para exportar
        $matriz_horario = call_user_func_array('array_map',array_merge(array(NULL),array_values($horario_traspuesto)));
        
        return $matriz_horario;
    }
    
}

/* End of file curso.php */
/* Location: ./application/models/curso.php */