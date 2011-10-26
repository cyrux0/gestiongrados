<?php


class Horarios extends MY_Controller{
    
    function  __construct(){
        parent::__construct();
        $this->layout = '';
    }
    
    public function index(){
        $this->layout = '';
        
        $this->load->view('horarios/index');
        
    }
    
    public function select_grupo($id_titulacion = '', $id_curso = ''){
        if(!$id_titulacion) redirect('titulaciones/select_titulacion/horarios/select_grupo/');
        if(!$id_curso) redirect('cursos/select_curso/horarios/select_grupo/' . $id_titulacion . '/');
        
        $titulacion = Doctrine::getTable("Titulacion")->find($id_titulacion);
        $horarios = Doctrine_Query::create()
            ->select('h.*')
            ->from('Horario h')
            ->where('h.id_curso = ?', $id_curso)
            ->andWhere('h.id_titulacion = ?', $id_titulacion)
            ->orderBy('h.num_curso_titulacion, h.semestre, h.num_grupo_titulacion')
            ->execute();
        
        $this->load->view('horarios/select_grupo', array('horarios' => $horarios, 'num_cursos' => $titulacion->num_cursos, 'id_titulacion' => $id_titulacion, 'id_curso' => $id_curso));
        
    }
    
    public function add_grupo($id_titulacion, $id_curso, $curso_titulacion, $semestre, $num_grupo){
        $horario = new Horario;
        $horario->id_curso = $id_curso;
        $horario->id_titulacion = $id_titulacion;
        $horario->num_curso_titulacion = $curso_titulacion;
        $horario->num_grupo_titulacion = $num_grupo;
        $horario->semestre = $semestre;
        
        $query_asignaturas = Doctrine_Query::create()
                            ->select('a.id, p.id, c.id, c.horas, c.grupos, c.alternas, c.actividad')
                            ->from('Asignatura a')
                            ->innerJoin('a.PlanesDocentes p')
                            ->innerJoin('p.planactividades c')
                            ->where("a.curso = ?", $curso_titulacion)
                            ->andWhere("a.titulacion_id = ?", $id_titulacion)
                            ->andWhere("a.semestre = ?", $semestre)
                            ->andWhere("p.id_curso = ?", $id_curso);
                            
        
        $asignaturas = $query_asignaturas->execute();

        foreach($asignaturas as $asignatura){
            foreach($asignatura->PlanesDocentes[0]->planactividades as $planactividad){
                if($planactividad->actividad == "teoria"){
                    $grupos_totales_teoria = $planactividad->grupos;
                }
            }
            
            foreach($asignatura->PlanesDocentes[0]->planactividades as $planactividad){
                if($planactividad->actividad == "teoria"){
                    $linea_horario = new LineaHorario;
                    $linea_horario->slot_minimo = $planactividad->horas_semanales;
                    $linea_horario->id_asignatura = $asignatura->id;
                    $linea_horario->actividad = $planactividad->actividad;
                    $linea_horario->num_grupo_actividad = $num_grupo;
                    $horario->lineashorario[] = $linea_horario;
                }else{
                    $por_asignar = ($planactividad->grupos - floor($planactividad->grupos/$grupos_totales_teoria)*($num_grupo-1));
                    $asignados = $planactividad->grupos - $por_asignar;
                    $grupos_corresp = floor($por_asignar/($grupos_totales_teoria-$num_grupo+1));
                    for($j = 0; $j < $grupos_corresp; $j++){
                        for($i = 0; $i < $planactividad->horas_semanales; $i += 0.5){
                            $linea_horario = new LineaHorario;
                            $linea_horario->slot_minimo = 0.5;
                            $linea_horario->id_asignatura = $asignatura->id;
                            $linea_horario->actividad = $planactividad->actividad;
                            $linea_horario->num_grupo_actividad = $asignados + $j + 1;
                            $horario->lineashorario[] = $linea_horario;                        
                        }
                    }
                }
            }
        }

        $horario->save();
        redirect('horarios/select_grupo/' . $id_titulacion . '/' . $id_curso);
    }

    public function asignar_aulas($id){
        $lineas = Doctrine_Query::create()
            ->select('l.id, l.actividad, l.num_grupo_actividad, l.id_aula, l.id_asignatura, a.nombre, l.id_horario')
            ->from('LineaHorario l')
            ->innerJoin('l.asignatura a')
            ->where('l.id_horario = ?', $id)
            ->groupBy('l.id_asignatura, l.actividad, l.num_grupo_actividad')
            ->execute();
        
        $aulas = Doctrine::getTable('Aula')->findAll();
        $array_aulas = array();
        foreach($aulas as $aula){
            $array_aulas[$aula->id] = $aula->nombre;
        }
        $this->load->view('horarios/asignar_aulas', array('lineas' => $lineas, 'aulas' => $array_aulas));
    }
    
    public function guardar_aulas(){
        $aulas = $this->input->post('aula');
        
        foreach($aulas as $key => $aula){
            list($asignatura, $actividad, $grupo) = explode('/', $key, 3);
            /*echo $asignatura . "\n";
            echo $actividad . "\n";
            echo $grupo . "\n";
            */
            $query = Doctrine_Query::create()
                ->update('LineaHorario')
                ->set('id_aula', $aula)
                ->where('id_asignatura = ?', array($asignatura))
                ->andWhere('actividad = ?', array($actividad))
                ->andWhere('num_grupo_actividad = ?', array($grupo));
                
            $rows = $query->execute();
        }
        
        redirect('horarios/edit/' . $this->input->post('id_horario'));
    }
        
    public function edit($id){
        
        $horario = Doctrine::getTable("Horario")->find($id);
        
        $asignaturas_por_asignar = array();
        $asignaturas_asignadas = array();
        
        foreach($horario->lineashorario as $lineahorario){
            if(!$lineahorario->hora_inicial){
                $array_linea_horario = $lineahorario->toArray();
                $array_linea_horario['nombre_asignatura'] = $lineahorario->asignatura->abreviatura . " (" . $lineahorario->actividad . " - " . $lineahorario->num_grupo_actividad . " ) ";
                $array_linea_horario['save_url'] = site_url("horarios/save_line/" . $lineahorario->id);
                $asignaturas_por_asignar[$lineahorario->id_asignatura . $lineahorario->actividad . $lineahorario->num_grupo_actividad][] = $array_linea_horario;
            }else{
                $array_linea_horario = $lineahorario->toArray();
                $array_linea_horario['nombre_asignatura'] = $lineahorario->asignatura->abreviatura . " (" . $lineahorario->actividad . " - " . $lineahorario->num_grupo_actividad . " ) ";
                $array_linea_horario['save_url'] = site_url("horarios/save_line/" . $lineahorario->id);
                $asignaturas_asignadas[] = $array_linea_horario;
            }
        }
        
        $this->load->view('horarios/edit', array('horario' => $horario, 'asignaturas_por_asignar' => $asignaturas_por_asignar, 'asignaturas_asignadas' => $asignaturas_asignadas));
        
    }

    public function ocupacion_aula($id_curso, $id_aula){
        $lineas_aulas = Doctrine_Query::create()
            ->select('l.id, l.hora_inicial, l.hora_final, l.dia_semana')
            ->from('LineaHorario l')
            ->innerJoin('l.horario h')
            ->where('h.id_curso = ?', $id_curso)
            ->andWhere('l.id_aula = ?', $id_aula)
            ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        
        echo json_encode($lineas_aulas);
    }
/*
    public function edit_teoria($id_tipo){
        $horario = Doctrine::getTable("Horario")->find($id_tipo);
        if(!$horario->horario_teoria and $horario->curso->num_semanas_teoria > 0){
            $horario->horario_teoria = new Horario;
            foreach($horario->lineashorario as $lineahorario){
                if($lineahorario->actividad == "teoria"){
                    $lineahorarioteoria = $lineahorario->copy();
                    $horario->horario_teoria->lineashorario[] = $lineahorarioteoria;
                }
            }
            $horario->save();
        }else{
            //Error
            echo "Error: este horario ya tiene un horario teoria";
        }
    }
*/

    public function check_horario($id){
        $horario = Doctrine::getTable("Horario")->find($id);
        $eventos = Doctrine_Query::create()
                    ->select("e.*")
                    ->from("Evento e")
                    ->where("e.curso_id = ?", $horario->id_curso)
                    ->execute();
        $curso = Doctrine::getTable("Curso")->find($horario->id_curso);

	$dias_totales = array_pad(array(), 5, $curso->num_semanas_semestre1);
        foreach($eventos as $evento){
	  $fecha_inicial = date_create_from_format("Y-m-d", $evento->fecha_inicial);
	  $fecha_final = date_create_from_format("Y-m-d", $evento->fecha_final);
	  $interval = new DateInterval("P1D");
	  do{
	    $dia_semana = $fecha_inicial->format("w");
	    $dia_semana = intval($dia_semana) - 1;
	    $dias_totales[$dia_semana] -= 1;
	    $fecha_inicial->add($interval);
	  }while($fecha_inicial <= $fecha_final);
        }

	unset($this->layout);
	$horas = array();
	foreach($horario->lineashorario as $lineahorario){
	    if(!isset($horas[$lineahorario->asignatura->nombre])) $horas[$lineahorario->asignatura->nombre] = array();
        if(!isset($horas[$lineahorario->asignatura->nombre][$lineahorario->actividad])) $horas[$lineahorario->asignatura->nombre][$lineahorario->actividad] = array();
        if(!isset($horas[$lineahorario->asignatura->nombre][$lineahorario->actividad][$lineahorario->num_grupo_actividad])) $horas[$lineahorario->asignatura->nombre][$lineahorario->actividad][$lineahorario->num_grupo_actividad] = 0;
        
	  $horas[$lineahorario->asignatura->nombre][$lineahorario->actividad][$lineahorario->num_grupo_actividad] += $lineahorario->slot_minimo*$dias_totales[$lineahorario->dia_semana];
	}
	
	$this->load->view('horarios/check', array('horas' => $horas));

        
    }
    
    public function save_line($id){
        
        $linea = Doctrine::getTable("LineaHorario")->find($id);
        $date_inicial = new DateTime();
        $date_inicial->setTime($this->input->post("hora_inicial"), $this->input->post("minuto_inicial"));
        $date_final = new DateTime();
        $date_final->setTime($this->input->post("hora_final"), $this->input->post("minuto_final"));
        $linea->hora_inicial = $date_inicial->format("H:i");
        $linea->hora_final = $date_final->format("H:i");
        $linea->dia_semana = $this->input->post("dia_semana");
        $linea->save();
    }
    
    public function delete($id_horario){
        $horario = Doctrine::getTable("Horario")->find($id_horario);
        $horario->lineashorario->delete();
        $horario->delete();
        redirect('/');
    }
}
