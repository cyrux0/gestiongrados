<?php

class Horarios extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->layout = '';
        $this->load->helper('resumen_asignatura_helper');
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
                    // Se asigna el número de grupos de esta actividad que tiene la asignatura (Se divide entre 2 si son semanas alternas, ya que los grupos irán de dos en dos)
                    $grupos = $planactividad->alternas? $planactividad->grupos / 2 : $planactividad->grupos;
                    // Se calcula el número de grupos por asignar, para ello se usa esta fórmula.
                    $por_asignar = ($grupos - floor($grupos / $grupos_totales_teoria) * ($num_grupo - 1));
                    // Aquí se calculan los que están ya asignados.
                    $asignados = $grupos - $por_asignar;
                    // Se dividen los que quedan entre el número de grupos de teoría que quedan por llegar todavía.
                    $grupos_corresp = floor($por_asignar / ($grupos_totales_teoria - $num_grupo + 1));
                    
                    // Por cada grupo correspondiente a este horario creamos una línea de horario y la asignamos al horario.
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

        // Obtenemos también las líneas compartidas
        $lineas_compartidas_sql = Doctrine_Query::create()
                ->select('c.id_plandocente, a.id, p.id, l.*, v.identificador, a.abreviatura')
                ->from('CursoCompartido c')
                ->innerJoin('c.plandocente p')
                ->innerJoin('p.Asignatura a')
                ->innerJoin('a.lineashorario l')
                ->innerJoin('l.actividad v')
                ->where('p.id_curso = ?', array($horario->id_curso))
                ->andWhere('c.num_curso_compartido = ?', array($horario->num_curso_titulacion));
        $lineas_sql = $lineas_compartidas_sql->getSqlQuery();
        $lineas_compartidas = $lineas_compartidas_sql->execute();
        
        $lineas_ = $lineas_compartidas[0]->plandocente->Asignatura->lineashorario;
        foreach($lineas_ as $linea_compartida)
        {
            if (!$linea_compartida->hora_inicial) {
                $array_linea_horario = $linea_compartida->toArray();
                $array_linea_horario['nombre_asignatura'] = $linea_compartida->asignatura->abreviatura . " (" . $linea_compartida->actividad->identificador . $linea_compartida->num_grupo_actividad . " ) ";
                $array_linea_horario['save_url'] = site_url("horarios/save_line/" . $linea_compartida->id);
                $asignaturas_por_asignar[$linea_compartida->id_asignatura . $linea_compartida->id_actividad . $linea_compartida->num_grupo_actividad][] = $array_linea_horario;
            } else {
                $array_linea_horario = $linea_compartida->toArray();
                $array_linea_horario['nombre_asignatura'] = $linea_compartida->asignatura->abreviatura . " (" . $linea_compartida->actividad->identificador . $linea_compartida->num_grupo_actividad . " ) ";
                $array_linea_horario['save_url'] = site_url("horarios/save_line/" . $linea_compartida->id);
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

        // Obtenemos el horario correspondiente a partir del id del horario tipo (si es que se ha creado ya)
        $query = Doctrine_Query::create()
                ->select('h.*')
                ->from('Horario h, horarioReference r')
                ->where('h.id = r.id_teoria')
                ->andWhere('r.id_tipo = ?', array($id_tipo))
                ->andWhere('h.num_semana = ?', array($num_semana))
                ->execute();

        // Obtenemos las fechas de la semana correspondiente al horario (lunes, fecha de comienzo de curso y fecha de fin de curso
        list($fecha_inicial, $fecha_lunes, $fecha_final) = dias_iniciales($horario->id_curso, $horario->num_semana, $horario->semestre);

        // Nos aseguramos que el número de la semana esté comprendido entre 0 y las semanas de teoría
        if ($horario->curso->num_semanas_teoria > 0 and $num_semana <= $horario->curso->num_semanas_teoria and $num_semana > 0) {
            // Si no se ha creado aun el horario lo creamos
            if (!$query->getFirst()) {
                $horario_teoria = new Horario;
                $horario_teoria->num_semana = $num_semana;
                $horario_teoria->id_curso = $horario->id_curso;
                $horario_teoria->id_titulacion = $horario->id_titulacion;
                $horario_teoria->num_curso_titulacion = $horario->num_curso_titulacion;
                $horario_teoria->semestre = $horario->semestre;
                $horario_teoria->num_grupo_titulacion = $horario->num_grupo_titulacion;

                // Copiamos cada una de las líneas de horario de teoría en el nuevo horario
                foreach ($horario->lineashorario as $lineahorario) {
                    if ($lineahorario->id_actividad == 1) {
                        $lineahorarioteoria = $lineahorario->copy();
                        
                        // Se comprueba que la fecha está disponible (han empezado las clases y no hay ninguna fiesta)
                        if (comprobar_fecha_linea($fecha_inicial, $fecha_lunes, $lineahorarioteoria->dia_semana, $horario->id_curso)) {
                            // En caso contrario ponemos los datos de la línea a null, lo que hará que aparezca sin colocar en el horario
                            $lineahorarioteoria->hora_inicial = null;
                            $lineahorarioteoria->hora_final = null;
                            $lineahorarioteoria->dia_semana = null;
                        }
                        // Cargamos la línea en el nuevo horario
                        $horario_teoria->lineashorario[] = $lineahorarioteoria;
                   }
                }
                // Asociamos el nuevo horario a su horario tipo.
                $horario->horarioteoria[] = $horario_teoria;
                $horario_teoria->save();
                $horario->save();
                // Cargamos la vista de edición
                $this->edit($horario_teoria->id);
            } else {
                // En caso de que ya esté creado el horario, simplemente cargamos la vista
                $this->edit($query->getFirst()->id);
            }
        } else {
            //Error
            echo "Error: Incorrecto";
        }
    }

    public function check_grupo($id) {
        $horario = Doctrine::getTable('Horario')->find($id);
        //if(count($horario->horariotipo)) redirect('404');
        $prueba = count($horario->horariotipo);
        //list($dias_totales, $dias_semanas_impares, $dias_semanas_pares) = horas_reales_impartidas($horario->id_curso, $horario->num_semana, $horario->semestre);
        
        $lineas = Doctrine_Query::create()
                ->select('l.id_asignatura')
                ->from('LineaHorario l')
                ->where('l.id_horario = ?', array($id))
                ->groupBy('l.id_asignatura')
                ->execute();

        $conjuntohoras = array();
        
        $this->load->helper('calendar_helper');
        
        foreach($lineas as $asignatura)
        {
            list($header, $arraygrupos, $horas) = resumen_asignatura($asignatura->id_asignatura, $horario->id_curso);
            $i = 0;
            $suma = array_map('array_sum', $horas);
            foreach($arraygrupos as $grupo){
                $planactividad = Doctrine_Query::create()
                        ->select('c.*')
                        ->from('PlanActividad c')
                        ->innerJoin('c.plandocente p')
                        ->where('p.id_asignatura = ?', array($asignatura->id_asignatura))
                        ->andWhere('p.id_curso = ?', array($horario->id_curso))
                        ->andWhere('c.id_actividad = ?', array($grupo[0]))
                        ->execute();
                $horas_reales[] = $planactividad->getFirst()->horas;
            }
            $horascongrupos = array($header, $suma, $horas_reales);
            $conjuntohoras[] = call_user_func_array('array_map', array_merge(array(NULL), $horascongrupos));
            $asignaturas[] = Doctrine::getTable('Asignatura')->find($asignatura->id)->nombre;
            // $conjuntohoras[] = horas_asignatura($dias_totales, $dias_semanas_impares, $dias_semanas_pares, $asignatura->id_asignatura, $horario->id_curso, $id);
        }
        
        unset($this->layout);
        
        $this->load->view('horarios/check', array('horas' => $conjuntohoras, 'asignaturas' => $asignaturas));
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
            list($fecha_inicial, $fecha_lunes, $fecha_final) = dias_iniciales($linea->horario->id_curso, $linea->horario->num_semana, $linea->horario->semestre);
            
            if(comprobar_fecha_linea($fecha_inicial, $fecha_lunes, $linea->dia_semana, $linea->horario->id_curso)){
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

    public function exportar($id_horario)
    {
        $horario = Doctrine::getTable('Horario')->find($id_horario);
        $curso = Doctrine::getTable('Curso')->find($horario->id_curso);
        $matriz = $curso->getMatrizHorario('id_horario', $id_horario, $horario->semestre, $horario->num_semana);
        $this->load->helper('importacion_csv_helper');
        exportador_csv('./application/downloads/temp.csv', $matriz);
        $data = file_get_contents('./application/downloads/temp.csv');
        $name = 'horario.csv';
        $this->load->helper('download');
        force_download($name, $data);
    }
}
