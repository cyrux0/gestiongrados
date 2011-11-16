<?php

class Horarios extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->layout = '';
    }

    public function index() {
        $this->layout = '';

        $this->load->view('horarios/index');
    }

    public function select_grupo($id_titulacion = '', $id_curso = '') {
        if (!$id_titulacion)
            redirect('titulaciones/select_titulacion/horarios/select_grupo/');
        if (!$id_curso)
            redirect('cursos/select_curso/horarios/select_grupo/' . $id_titulacion . '/');

        // Extraemos la titulación a la que se le asignarán los horarios
        $titulacion = Doctrine::getTable("Titulacion")->find($id_titulacion);

        // Buscamos el curso para saber el número de semanas de teoría que tendrá.
        $curso = Doctrine::getTable("Curso")->find($id_curso);
        $num_semanas_teoria = $curso->num_semanas_teoria;

        // Esa consulta seguramente sea inútil
        $horarios = Doctrine_Query::create()
                ->select('h.*')
                ->from('Horario h')
                ->where('h.id_curso = ?', $id_curso)
                ->andWhere('h.id_titulacion = ?', $id_titulacion)
                ->orderBy('h.num_curso_titulacion, h.semestre, h.num_grupo_titulacion')
                ->execute();


        // Creamos un array cursos que será el que pasemos a la vista
        $cursos = array();
        // Guardamos el número de cursos que será el tamaño del array anterior.
        $num_cursos = $titulacion->num_cursos;

        for ($i = 0; $i < $num_cursos; $i++) {
            // Consultamos el número de grupos creados que tiene la titulación 
            $num_grupos = Doctrine_Query::create()
                    ->select('MAX(h.num_grupo_titulacion) as grupos')
                    ->from('Horario h')
                    ->where('h.id_curso = ?', $id_curso)
                    ->andWhere('h.id_titulacion = ?', $id_titulacion)
                    ->andWhere('h.num_curso_titulacion = ?', $i + 1)
                    ->execute();

            // Creamos un array en la posición $i, que corresponderá a los datos del curso $i+1
            $cursos[$i] = array();
            // Guardamos el número de grupos correspondiente a ese curso, sino 0, para saber que esa fila no tendrá nada.
            $cursos[$i]['num_grupos'] = $num_grupos[0]->grupos ? : 0;

            // Si hay grupos buscamos los ids de los horarios 
            if ($num_grupos[0]->grupos) {
                // Quizás habría que meter aquí una comparación para asegurarnos de que el horario es un horario tipo.
                $horarios = Doctrine_Query::create()
                        ->select('h.id')
                        ->from('Horario h')
                        ->where('h.id_curso = ?', $id_curso)
                        ->andWhere('h.id_titulacion = ?', $id_titulacion)
                        ->andWhere('h.num_grupo_titulacion = ?', $num_grupos[0]->grupos)
                        ->andWhere('h.num_curso_titulacion = ?', $i + 1)
                        ->andWhere('h.num_semana = ?', $num_semanas_teoria + 1) // Para asegurarnos que pertenece a la siguiente semana después de las de teoría.
                        ->orderBy('h.num_curso_titulacion, h.semestre, h.num_grupo_titulacion')
                        ->execute();

                $cursos[$i]['id_horario_sem1'] = $horarios[0]->id;
                $cursos[$i]['id_horario_sem2'] = $horarios[1]->id;
            } else {
                $cursos[$i]['id_horario_sem1'] = 0;
                $cursos[$i]['id_horario_sem2'] = 0;
            }
            $cursos[$i]['mas_grupos'] = true; // Esto habría que ponerlo a false si se ha alcanzado el máximo de grupos.
        }


        $this->load->view('horarios/select_grupo', array('cursos' => $cursos, 'id_titulacion' => $id_titulacion, 'id_curso' => $id_curso, 'num_semanas_teoria' => $num_semanas_teoria));
    }

    public function crear_grupos($id_titulacion, $id_curso) {
        $horarios = Doctrine_Query::create()
                ->select('h.*')
                ->from('Horario h')
                ->where('h.id_titulacion = ?', $id_titulacion)
                ->andWhere('h.id_curso = ?', $id_curso)
                ->execute();

        if (count($horarios))
            redirect("horarios/select_grupo/$id_titulacion/$id_curso");

        $titulacion = Doctrine::getTable('Titulacion')->find($id_titulacion);

        $this->load->view('horarios/configuracion_grupos', array('titulacion' => $titulacion));
    }

    public function add_grupo($id_titulacion, $id_curso, $curso_titulacion, $num_grupo) {
        $curso = Doctrine::getTable('Curso')->find($id_curso);

        $horario_semestre1 = new Horario;
        $horario_semestre1->id_curso = $id_curso;
        $horario_semestre1->id_titulacion = $id_titulacion;
        $horario_semestre1->num_curso_titulacion = $curso_titulacion;
        $horario_semestre1->num_grupo_titulacion = $num_grupo;
        $horario_semestre1->semestre = "primero";
        $horario_semestre1->num_semana = $curso->num_semanas_teoria + 1;

        $horario_semestre2 = new Horario;
        $horario_semestre2->id_curso = $id_curso;
        $horario_semestre2->id_titulacion = $id_titulacion;
        $horario_semestre2->num_curso_titulacion = $curso_titulacion;
        $horario_semestre2->num_grupo_titulacion = $num_grupo;
        $horario_semestre2->semestre = "segundo";
        $horario_semestre2->num_semana = $curso->num_semanas_teoria + 1;

        $query_asignaturas = Doctrine_Query::create()
                ->select('a.id, p.id, c.id, c.horas, c.grupos, c.alternas, c.id_actividad')
                ->from('Asignatura a')
                ->innerJoin('a.PlanesDocentes p')
                ->innerJoin('p.planactividades c')
                ->where("a.curso = ?", $curso_titulacion)
                ->andWhere("a.titulacion_id = ?", $id_titulacion)
                ->andWhere("p.id_curso = ?", $id_curso);


        $asignaturas = $query_asignaturas->execute();

        $asignatura = $asignaturas[0];

        foreach ($asignatura->PlanesDocentes[0]->planactividades as $planactividad) {
            if ($planactividad->id_actividad == 1) {
                $grupos_totales_teoria = $planactividad->grupos; // Esto habría que sacarlo de otro sitio pero de momento se deja ahí.
            }
        }

        foreach ($asignaturas as $asignatura) {
            foreach ($asignatura->PlanesDocentes[0]->planactividades as $planactividad) {
                if ($planactividad->id_actividad == 1) { // Teoría
                    for ($i = 0; $i < $planactividad->horas_semanales; $i += 0.5) {
                        $linea_horario = new LineaHorario;
                        $linea_horario->slot_minimo = 0.5;
                        $linea_horario->id_asignatura = $asignatura->id;
                        $linea_horario->id_actividad = $planactividad->id_actividad;
                        $linea_horario->num_grupo_actividad = $num_grupo;
                        if ($asignatura->semestre == "primero")
                            $horario_semestre1->lineashorario[] = $linea_horario;
                        else
                            $horario_semestre2->lineashorario[] = $linea_horario;
                    }
                }else {
                    $por_asignar = ($planactividad->grupos - floor($planactividad->grupos / $grupos_totales_teoria) * ($num_grupo - 1));
                    $asignados = $planactividad->grupos - $por_asignar;
                    $grupos_corresp = floor($por_asignar / ($grupos_totales_teoria - $num_grupo + 1));
                    for ($j = 0; $j < $grupos_corresp; $j++) {
                        $linea_horario = new LineaHorario;
                        $linea_horario->slot_minimo = $planactividad->horas_semanales;
                        $linea_horario->id_asignatura = $asignatura->id;
                        $linea_horario->id_actividad = $planactividad->id_actividad;
                        $linea_horario->num_grupo_actividad = $asignados + $j + 1;
                        if ($asignatura->semestre == "primero")
                            $horario_semestre1->lineashorario[] = $linea_horario;
                        else
                            $horario_semestre2->lineashorario[] = $linea_horario;
                    }
                }
            }
        }

        $horario_semestre1->save();
        $horario_semestre2->save();

        redirect('horarios/select_grupo/' . $id_titulacion . '/' . $id_curso);
    }

    public function asignar_aulas($id) {
        $lineas = Doctrine_Query::create()
                ->select('l.id, l.id_actividad, l.num_grupo_actividad, l.id_aula, l.id_asignatura, a.nombre, l.id_horario')
                ->from('LineaHorario l')
                ->innerJoin('l.asignatura a')
                ->where('l.id_horario = ?', $id)
                ->groupBy('l.id_asignatura, l.id_actividad, l.num_grupo_actividad')
                ->execute();

        $aulas = Doctrine::getTable('Aula')->findAll();
        $array_aulas = array();
        foreach ($aulas as $aula) {
            $array_aulas[$aula->id] = $aula->nombre;
        }
        $this->load->view('horarios/asignar_aulas', array('lineas' => $lineas, 'aulas' => $array_aulas));
    }

    public function guardar_aulas() {
        $aulas = $this->input->post('aula');

        foreach ($aulas as $key => $aula) {
            list($asignatura, $actividad, $grupo) = explode('/', $key, 3);
            /* echo $asignatura . "\n";
              echo $actividad . "\n";
              echo $grupo . "\n";
             */
            $query = Doctrine_Query::create()
                    ->update('LineaHorario')
                    ->set('id_aula', $aula)
                    ->where('id_asignatura = ?', array($asignatura))
                    ->andWhere('id_actividad = ?', array($actividad))
                    ->andWhere('num_grupo_actividad = ?', array($grupo));

            $rows = $query->execute();
        }

        redirect('horarios/edit/' . $this->input->post('id_horario'));
    }

    public function edit($id) {

        $horario = Doctrine::getTable("Horario")->find($id);

        $asignaturas_por_asignar = array();
        $asignaturas_asignadas = array();

        foreach ($horario->lineashorario as $lineahorario) {
            if (!$lineahorario->hora_inicial) {
                $array_linea_horario = $lineahorario->toArray();
                $array_linea_horario['nombre_asignatura'] = $lineahorario->asignatura->abreviatura . " (" . $lineahorario->actividad->identificador . $lineahorario->num_grupo_actividad . " ) ";
                $array_linea_horario['save_url'] = site_url("horarios/save_line/" . $lineahorario->id);
                $asignaturas_por_asignar[$lineahorario->id_asignatura . $lineahorario->id_actividad . $lineahorario->num_grupo_actividad][] = $array_linea_horario;
            } else {
                $array_linea_horario = $lineahorario->toArray();
                $array_linea_horario['nombre_asignatura'] = $lineahorario->asignatura->abreviatura . " (" . $lineahorario->actividad->identificador . $lineahorario->num_grupo_actividad . " ) ";
                $array_linea_horario['save_url'] = site_url("horarios/save_line/" . $lineahorario->id);
                $array_linea_horario['evento_especial'] = 0;

                $asignaturas_asignadas[] = $array_linea_horario;
            }
        }

        // Si estamos en la primera semana hay que bloquear los días no lectivos en la vista del horario
        if ($horario->num_semana == 1) {
            // Buscamos la fecha inicial del curso para ver cuantos días hay que bloquear antes del primer día (puede no ser ninguno)(Contando el número de días desde el lunes
            $curso = Doctrine::getTable('Curso')->find($horario->id_curso);

            // Miramos a que semestre pertenece el horario para coger la fecha de inicio de ese semestre
            if ($horario->semestre == "primero") {
                $fecha_inicial = date_create_from_format("Y-m-d", $curso->inicio_semestre1);
            } else {
                $fecha_inicial = date_create_from_format("Y-m-d", $curso->inicio_semestre2);
            }
            $dia_semana = $fecha_inicial->format("N") - 1;

            for ($i = 0; $i < $dia_semana; $i++) {
                $array_linea = array('evento_especial' => 1, 'hora_inicial' => $curso->hora_inicial, 'hora_final' => $curso->hora_final, 'nombre_asignatura' => 'No lectivo', 'id' => 1000 + $i, 'id_actividad' => 0, 'dia_semana' => $i);
                $asignaturas_asignadas[] = $array_linea;
            }
        }


        $actividades = Doctrine::getTable('Actividad')->findAll();
        $aulas = Doctrine::getTable('Aula')->findAll();
        $array_aulas = array();
        foreach ($actividades as $actividad) {
            $array_aulas[$actividad->id] = array();
            foreach ($actividad->aulas as $aula) {
                $array_aulas[$actividad->id][$aula->id] = $aula->nombre;
            }
        }

        $aulas_total = array();
        foreach ($aulas as $aula) {
            $aulas_total[$aula->id] = $aula->nombre;
        }

        $this->load->view('horarios/edit', array('horario' => $horario, 'asignaturas_por_asignar' => $asignaturas_por_asignar, 'asignaturas_asignadas' => $asignaturas_asignadas, 'aulas' => $array_aulas, 'aulastotal' => $aulas_total));
    }

    public function ocupacion_aula($id_curso, $id_aula) {
        $curso = Doctrine::getTable('Curso')->find($id_curso);
        $lineas_aulas = Doctrine_Query::create()
                ->select('l.id, l.hora_inicial, l.hora_final, l.dia_semana')
                ->from('LineaHorario l')
                ->innerJoin('l.horario h')
                ->where('h.id_curso = ?', $id_curso)
                ->andWhere('h.num_semana = ?', $curso->num_semanas_teoria + 1)
                ->andWhere('l.id_aula = ?', $id_aula)
                ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        unset($this->layout);
        echo json_encode($lineas_aulas);
    }

    public function edit_teoria($id_tipo, $num_semana) {
        $horario = Doctrine::getTable("Horario")->find($id_tipo);

        $query = Doctrine_Query::create()
                ->select('h.*')
                ->from('Horario h, horarioReference r')
                ->where('h.id = r.id_teoria')
                ->andWhere('r.id_tipo = ?', array($id_tipo))
                ->andWhere('h.num_semana = ?', array($num_semana))
                ->execute();

        list($fecha_inicial, $fecha_lunes, $fecha_final) = $this->_dias_iniciales($horario->id_curso, $horario->num_semana, $horario->semestre);

        if ($horario->curso->num_semanas_teoria > 0 and $num_semana <= $horario->curso->num_semanas_teoria) {
            if (!$query->getFirst()) {
                $horario_teoria = new Horario;
                $horario_teoria->num_semana = $num_semana;
                $horario_teoria->id_curso = $horario->id_curso;
                $horario_teoria->id_titulacion = $horario->id_titulacion;
                $horario_teoria->num_curso_titulacion = $horario->num_curso_titulacion;
                $horario_teoria->semestre = $horario->semestre;
                $horario_teoria->num_grupo_titulacion = $horario->num_grupo_titulacion;

                foreach ($horario->lineashorario as $lineahorario) {
                    if ($lineahorario->id_actividad == 1) {
                        $lineahorarioteoria = $lineahorario->copy();
                        
                        if ($this->_comprobar_fecha_linea($fecha_inicial, $fecha_lunes, $lineahorarioteoria->dia_semana, $horario->id_curso)) {
                            $lineahorarioteoria->hora_inicial = null;
                            $lineahorarioteoria->hora_final = null;
                            $lineahorarioteoria->dia_semana = null;
                        }

                        $horario_teoria->lineashorario[] = $lineahorarioteoria;
                    }
                }
                $horario->horarioteoria[] = $horario_teoria;
                $horario_teoria->save();
                $horario->save();
                $this->edit($horario_teoria->id);
            } else {
                $this->edit($query->getFirst()->id);
            }
        } else {
            //Error
            echo "Error: Incorrecto";
        }
    }

    public function check_horario($id) {
        $horario = Doctrine::getTable("Horario")->find($id);
        $eventos = Doctrine_Query::create()
                ->select("e.*")
                ->from("Evento e")
                ->where("e.curso_id = ?", $horario->id_curso)
                ->execute();
        $curso = Doctrine::getTable("Curso")->find($horario->id_curso);

        $dias_totales = array_pad(array(), 5, $curso->num_semanas_semestre1);
        foreach ($eventos as $evento) {
            $fecha_inicial = date_create_from_format("Y-m-d", $evento->fecha_inicial);
            $fecha_final = date_create_from_format("Y-m-d", $evento->fecha_final);
            $interval = new DateInterval("P1D");
            do {
                $dia_semana = $fecha_inicial->format("w");
                $dia_semana = intval($dia_semana) - 1;
                $dias_totales[$dia_semana] -= 1;
                $fecha_inicial->add($interval);
            } while ($fecha_inicial <= $fecha_final);
        }

        unset($this->layout);
        $horas = array();
        foreach ($horario->lineashorario as $lineahorario) {
            if (!isset($horas[$lineahorario->asignatura->nombre]))
                $horas[$lineahorario->asignatura->nombre] = array();
            if (!isset($horas[$lineahorario->asignatura->nombre][$lineahorario->id_actividad]))
                $horas[$lineahorario->asignatura->nombre][$lineahorario->id_actividad] = array();
            if (!isset($horas[$lineahorario->asignatura->nombre][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad]))
                $horas[$lineahorario->asignatura->nombre][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad] = array('reales' => 0, 'planificadas' => 0);

            $horas[$lineahorario->asignatura->nombre][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad]['reales'] += $lineahorario->slot_minimo * $dias_totales[$lineahorario->dia_semana];
            if (!$horas[$lineahorario->asignatura->nombre][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad]['planificadas']) {
                $planactividad = Doctrine_Query::create()
                        ->select('c.*')
                        ->from('PlanActividad c')
                        ->innerJoin('c.plandocente p')
                        ->where('p.id_asignatura = ?', array($lineahorario->id_asignatura))
                        ->andWhere('p.id_curso = ?', array($horario->id_curso))
                        ->andWhere('c.id_actividad = ?', array($lineahorario->id_actividad))
                        ->execute();

                $horas[$lineahorario->asignatura->nombre][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad]['planificadas'] = $planactividad->getFirst()->horas;
            }
        }

        $this->load->view('horarios/check', array('horas' => $horas));
    }

    public function save_line($id) {

        $linea = Doctrine::getTable("LineaHorario")->find($id);
        $hora_inicial = new DateTime();
        $hora_inicial->setTime($this->input->post("hora_inicial"), $this->input->post("minuto_inicial"));
        $hora_final = new DateTime();
        $hora_final->setTime($this->input->post("hora_final"), $this->input->post("minuto_final"));
        $linea->hora_inicial = $hora_inicial->format("H:i");
        $linea->hora_final = $hora_final->format("H:i");
        $linea->dia_semana = $this->input->post("dia_semana");
        $linea->id_aula = $this->input->post('aula')? : $linea->id_aula;
        $linea->color = $this->input->post('color');
        
        
        
        
        $success = array('success' => 1);

        if($linea->horario->num_semana <= $linea->horario->curso->num_semanas_teoria){
            list($fecha_inicial, $fecha_lunes, $fecha_final) = $this->_dias_iniciales($linea->horario->id_curso, $linea->horario->num_semana, $linea->horario->semestre);
            
            if($this->_comprobar_fecha_linea($fecha_inicial, $fecha_lunes, $linea->dia_semana, $linea->horario->id_curso)){
                $success['success'] = 0;
            }   
        }
        
        if ($linea->id_actividad == 1) {
            // Hora ocupada en el mismo horario
            $lineas = Doctrine_Query::create()
                    ->select('l.*')
                    ->from('LineaHorario l')
                    ->where('l.hora_inicial >= ? AND l.hora_inicial < ?', array($linea->hora_inicial, $linea->hora_final))
                    ->orWhere('l.hora_final > ? AND l.hora_final <= ?', array($linea->hora_inicial, $linea->hora_final))
                    ->orWhere('l.hora_inicial <= ? AND l.hora_final > ?', array($linea->hora_inicial, $linea->hora_inicial))
                    ->orWhere('l.hora_inicial < ? AND l.hora_final >= ?', array($linea->hora_final, $linea->hora_final))
                    ->having('l.dia_semana = ? AND l.id_horario = ?', array($linea->dia_semana, $linea->id_horario))
                    ->execute();
        }

        // Hora ocupada en el mismo aula
        $query_aula = Doctrine_Query::create()
                ->select('l.*, h.*')
                ->from('LineaHorario l')
                ->innerJoin('l.horario h')
                ->where('l.hora_inicial >= ? AND l.hora_inicial < ?', array($linea->hora_inicial, $linea->hora_final))
                ->orWhere('l.hora_final > ? AND l.hora_final <= ?', array($linea->hora_inicial, $linea->hora_final))
                ->having('l.dia_semana = ? AND h.id_curso = ? AND l.id_aula = ? AND h.num_semana = ?', array($linea->dia_semana, $linea->horario->id_curso, $linea->id_aula, $linea->horario->num_semana));

        $lineas_aula = $query_aula->execute();

        if (isset($lineas)) {
            if ($lineas->count()) {
                $success['success'] = 0;
                $success['count'] = $lineas->count();
            }
        }

        if (!$linea->isValid() or $lineas_aula->count() or !$success['success']) {
            $success['success'] = 0;

            $success['color'] = $linea->color;
        } else {
            $linea->save();
        }
        unset($this->layout);
        echo json_encode($success);
    }
    

    public function delete($id_horario) {
        $horario = Doctrine::getTable("Horario")->find($id_horario);
        $horario->lineashorario->delete();
        $horario->delete();
        redirect('/');
    }

    public function delete_line($id_line) {
        $line = Doctrine::getTable("LineaHorario")->find($id_line);
        $line->hora_inicial = null;
        $line->hora_final = null;
        $line->dia_semana = null;
        $line->save();

        unset($this->layout);
        redirect('horarios/edit/' . $line->id_horario);
    }

    function _dias_iniciales($id_curso, $num_semana, $semestre) {

        $curso = Doctrine::getTable('Curso')->find($id_curso);

        $date_sem1 = date_create_from_format("Y-m-d", $curso->inicio_semestre1);
        $date_sem2 = date_create_from_format("Y-m-d", $curso->inicio_semestre2);


// Buscamos la fecha inicial del curso para ver cuantos días hay que bloquear antes del primer día (puede no ser ninguno)(Contando el número de días desde el lunes
        if ($num_semana == 1) {
            if ($semestre == "primero") {
                $dia_semana = $date_sem1->format("N");
                $fecha_inicial = DateTime::createFromFormat("Y-m-d", $date_sem1->format("Y-m-d"));
            } else {
                $dia_semana = $date_sem2->format("N");
                $fecha_inicial = DateTime::createFromFormat("Y-m-d", $date_sem2->format("Y-m-d"));
                $date_lunes = DateTime::createFromFormat("Y-m-d", $date_sem2->format("Y-m-d"));
                $asd = new DateTime;
            }
            $date_lunes = $date_lunes->sub(new DateInterval("P" . ($dia_semana - 1) . "D"));
        } else {
            if ($semestre == "primero") {
                $dia_semana = $date_sem1->format("N");
                $fecha_inicial = $date_sem1->add(new DateInterval('P' . (7 - $dia_semana + 1) . 'D'));
            } else {
                $dia_semana = $date_sem2->format("N");
                $fecha_inicial = $date_sem2->add(new DateInterval('P' . (7 - $dia_semana + 1) . 'D'));
            }
            $date_lunes = DateTime::createFromFormat("Y-m-d", $fecha_inicial->format("Y-m-d"));
        }
        $fecha_final = DateTime::createFromFormat("Y-m-d", $date_lunes->format("Y-m-d"));
        $fecha_final = $fecha_final->add(new DateInterval('P5D'));

        return array($fecha_inicial, $date_lunes, $fecha_final);
    }
    
    function _comprobar_fecha_linea($fecha_inicial, $fecha_lunes, $dia_semana, $id_curso) {
        $date_to_add = DateTime::createFromFormat("Y-m-d", $fecha_lunes->format("Y-m-d"));
        $fecha_linea = $date_to_add->add(new DateInterval("P" . $dia_semana . "D"));
        
        $eventos = Doctrine_Query::create()
                                ->select("e.*")
                                ->from("Evento e")
                                ->where("e.curso_id = ?", array($id_curso))
                                ->andWhere("? BETWEEN fecha_inicial AND fecha_final", array($fecha_linea->format("Y-m-d")))
                                ->execute();
        
        return $eventos->count() or $fecha_linea < $fecha_inicial;
    }
    
    
}
