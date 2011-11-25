<?php

function horas_asignatura($dias_totales, $dias_semanas_impares, $dias_semanas_pares, $id_asignatura, $id_curso, $id_horario = null){
            if($id_horario){
            $lineashorario = Doctrine_Query::create()
                                ->select('l.*')
                                ->from('LineaHorario l')
                                ->innerJoin('l.horario h')
                                ->where('h.id = ?', array($id_horario))
                                ->andWhere('l.id_asignatura = ?', array($id_asignatura))
                                ->andWhere('l.hora_inicial IS NOT NULL')
                                ->execute();
        }else{
            $lineashorario = Doctrine_Query::create()
                                ->select('l.*')
                                ->from('LineaHorario l')
                                ->innerJoin('l.horario h')
                                ->where('h.id_curso = ?', array($id_curso))
                                ->andWhere('l.id_asignatura = ?', array($id_asignatura))
                                ->andWhere('l.hora_inicial IS NOT NULL')
                                ->execute();
        }
        
        if(count($lineashorario)){
            $horas = array('nombre_asignatura' => $lineashorario[0]->asignatura->nombre, 'id_asignatura' => $lineashorario[0]->id_asignatura, 'horas' => array());
        
        
            foreach($lineashorario as $lineahorario)
            {
                if (!isset($horas['horas'][$lineahorario->id_actividad]))
                    $horas['horas'][$lineahorario->id_actividad] = array();
                if (!isset($horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad]))
                    $horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad] = array('reales' => 0, 'planificadas' => 0);
                $planactividad = Doctrine_Query::create()
                            ->select('c.*')
                            ->from('PlanActividad c')
                            ->innerJoin('c.plandocente p')
                            ->where('p.id_asignatura = ?', array($lineahorario->id_asignatura))
                            ->andWhere('p.id_curso = ?', array($id_curso))
                            ->andWhere('c.id_actividad = ?', array($lineahorario->id_actividad))
                            ->execute();
                if($planactividad->getFirst()->alternas){
                    if(!isset($horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad*2-1])) $horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad*2-1] = array('reales' => 0, 'planificadas' => 0);
                    if(!isset($horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad*2])) $horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad*2] = array('reales' => 0, 'planificadas' => 0);
                    $horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad*2-1]['reales'] += $lineahorario->slot_minimo * $dias_semanas_impares[$lineahorario->dia_semana];
                    $horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad*2]['reales'] += $lineahorario->slot_minimo * $dias_semanas_pares[$lineahorario->dia_semana];
                }else{
                    $horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad]['reales'] += $lineahorario->slot_minimo * $dias_totales[$lineahorario->dia_semana];
                }

                if (!$horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad]['planificadas']) {


                    $horas['horas'][$lineahorario->id_actividad][$lineahorario->num_grupo_actividad]['planificadas'] = $planactividad->getFirst()->horas;
                }
            }
            /*
             * @todo Las horas de prácticas no deberían computar todas las semanas, habría que restarles las semanas de solo teoría.
             */
            return $horas;
        }else{
            return array();
        }
}

function horas_reales_impartidas($id_curso, $num_semana, $semestre)
    {
        
        $eventos = Doctrine_Query::create()
                ->select("e.*")
                ->from("Evento e")
                ->where("e.curso_id = ?", $id_curso)
                ->execute();
        $curso = Doctrine::getTable("Curso")->find($id_curso);
        
        // Obtenemos las fechas claves del curso
        list($dummy1, $fecha_lunes, $dummy2) = dias_iniciales($id_curso, $num_semana, $semestre);
        // Rellenamos un array de 5 posiciones con el número de semanas totales del semestre (se irá descontando a medida que vayamos encontrando fiestas)
        if($semestre == "primero"){
            $semanas = $curso->num_semanas_semestre1;
            $semanas_no_teoria = $curso->num_semanas_semestre1 - $curso->num_semanas_teoria;
        }else{
            $semanas = $curso->num_semanas_semestre2;
            $semanas_no_teoria = $curso->num_semanas_semestre2 - $curso->num_semanas_teoria;
        }
        
        $dias_totales = array_pad(array(), 5, $semanas);
        $dias_semanas_impares = array_pad(array(), 5, ceil($semanas_no_teoria/2));
        $dias_semanas_pares = array_pad(array(), 5, floor($semanas_no_teoria/2));
        // Aquí falta una cosa importante, y es diferenciar que el evento sea del primer semestre
        foreach ($eventos as $evento) {
            $fecha_inicial = date_create_from_format("Y-m-d", $evento->fecha_inicial);
            $fecha_final = date_create_from_format("Y-m-d", $evento->fecha_final);
            $interval = new DateInterval("P1D");
            do {
                $dia_semana = $fecha_inicial->format("w");
                $dia_semana = intval($dia_semana) - 1;
                $interval = date_diff($fecha_inicial, $fecha_lunes, true);
                $numero_semana = floor(intval($interval->format('%d'))/7);
                // Si la diferencia es par, estamos en semana impar
                if($numero_semana % 2 == 0){
                    if(isset($dias_semanas_impares[$dia_semana])){
                        $dias_semanas_impares[$dia_semana] -= 1;
                    }
                }else{
                    if(isset($dias_semanas_pares[$dia_semana])){
                        $dias_semanas_pares[$dia_semana] -= 1;
                    }
                }
                if(isset($dias_totales[$dia_semana])){
                    $dias_totales[$dia_semana] -= 1;
                }
                $fecha_inicial->add($interval);
            } while ($fecha_inicial <= $fecha_final);
        }
        
        return array($dias_totales, $dias_semanas_impares, $dias_semanas_pares);
}

function dias_iniciales($id_curso, $num_semana, $semestre) {

    // Obtenemos el curso
    $curso = Doctrine::getTable('Curso')->find($id_curso);

    // Extraemos las dos fechas iniciales de cada semestre
    $fecha_inicio_sem1 = date_create_from_format("Y-m-d", $curso->inicio_semestre1);
    $fecha_inicio_sem2 = date_create_from_format("Y-m-d", $curso->inicio_semestre2);
    $fecha_fin_sem1 = date_create_from_format("Y-m-d", $curso->fin_semestre1);
    $fecha_fin_sem2 = date_create_from_format("Y-m-d", $curso->fin_semestre2);
    // Buscamos la fecha inicial del curso para ver cuantos días hay que bloquear antes del primer día (puede no ser ninguno)(Contando el número de días desde el lunes)
    if ($semestre == "primero") {
        $dia_semana = $fecha_inicio_sem1->format("N");
        $fecha_inicial = DateTime::createFromFormat("Y-m-d", $fecha_inicio_sem1->format("Y-m-d"));
        $fecha_final = $fecha_fin_sem1;
    } else {
        $dia_semana = $fecha_inicio_sem2->format("N");
        $fecha_inicial = DateTime::createFromFormat("Y-m-d", $fecha_inicio_sem2->format("Y-m-d"));
        $fecha_final = $fecha_fin_sem2;
    }
    $date_lunes = DateTime::createFromFormat("Y-m-d", $fecha_inicial->format("Y-m-d"));
    $date_lunes->sub(new DateInterval("P" . ($dia_semana - 1) . "D"));
    $date_lunes->add(new DateInterval('P' . 7*($num_semana-1) . 'D'));
    
    // Dada una semana, se devuelve, la fecha inicial del curso, el lunes de esa semana, y la fecha final del curso
    return array($fecha_inicial, $date_lunes, $fecha_final);
}
    
function comprobar_fecha_linea($fecha_inicial, $fecha_lunes, $dia_semana, $id_curso) {
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
?>
