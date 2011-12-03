<?php

function matriz_producto_calendario($id_curso, $semestre)
{
    
    $curso = Doctrine::getTable('Curso')->find($id_curso);
    
    // Guardamos el número de semanas totales del semestre correspondiente
    if($semestre == "primero")
    {
        $num_semanas = $curso->num_semanas_semestre1;
    }
    else
    {
        $num_semanas = $curso->num_semanas_semestre2;
    }
    
    // Rellenamos una matriz de num_semanas x 5 de 1's
    $matriz_producto = array_fill(0, $num_semanas, array_fill(0, 5, 1));
    
    $CI = & get_instance();
    $CI->load->helper('resumen_asignatura_helper');
    // Obtenemos los días correspondientes a la primera semana
    list($fecha_inicial, $fecha_lunes, $fecha_final) = dias_iniciales($id_curso, 1, $semestre);
    
    // La lista de eventos
    $eventos = Doctrine_Query::create()
                ->select("e.*")
                ->from("Evento e")
                ->where("e.curso_id = ?", $id_curso)
                ->andWhere('e.fecha_final < ?', $fecha_final->format('Y-m-d'))
                ->execute();
    
    // Los días anteriores a comienzo del curso se ponen a 0
    for($i = 0; $i< intval($fecha_inicial->format("w"))-1; $i++)
    {
        $matriz_producto[0][$i] = 0;
    }
    
    // Recorremos todos los eventos poniendo a 0 los días que correspondan
    foreach($eventos as $evento)
    {
        $fecha_inicial_evento = date_create_from_format("Y-m-d", $evento->fecha_inicial);
        $fecha_final_evento = date_create_from_format("Y-m-d", $evento->fecha_final);
        $interval = new DateInterval("P1D");
        do {
            $dia_semana = $fecha_inicial_evento->format("w");
            $dia_semana = intval($dia_semana) - 1;
            $interval = date_diff($fecha_inicial_evento, $fecha_lunes, true);
            $numero_semana = floor(intval($interval->format('%d'))/7);
            // Si la diferencia es par, estamos en semana impar
            $matriz_producto[$numero_semana][$dia_semana] = 0;
            $fecha_inicial_evento->add($interval);
        } while ($fecha_inicial_evento <= $fecha_final_evento);       
    }
    
    // Ponemos a 0 los días sobrantes de la matriz, desde el fin de curso al viernes siguiente.
    for($i = intval($fecha_final->format("w")); $i<5; $i++)
    {
        $matriz_producto[$num_semanas][$i] = 0;
    }

    return $matriz_producto;
}


/**
 * Helper para calcular las horas de una asignatura en un curso, accede a los diferentes horarios y hace el cálculo.
 * 
 * @param integer $id_asignatura Identificador de la asignatura a la que se va a calcular el resumen.
 * @param integer $id_curso Identificador del curso al que se va a calcular el resumen.
 * @return array Devuelve un array de 3 arrays, uno con las cabeceras que indican los grupos, otro con la actividad y el grupo de cada columna y otro con las horas por semana.
 */
function resumen_asignatura($id_asignatura, $id_curso)
{
    // Obtenemos los objetos asignatura y curso.
    $asignatura = Doctrine::getTable('Asignatura')->find($id_asignatura);
    $curso = Doctrine::getTable('Curso')->find($id_curso);
    
    // Obtenemos la matriz de unos y ceros con los eventos del calendario marcados para el semestre y curso correspondiente.
    // Será una matriz de num_semanas x dias_semana (5)
    $matriz_producto = matriz_producto_calendario($id_curso, $asignatura->semestre);
    
    // Obtenemos todos los grupos de todas las actividades (una fila por actividad y grupo, para eso el groupBy)
    $grupos_qry = Doctrine_Query::create()
            ->select('l.id_actividad, l.num_grupo_actividad')
            ->from('LineaHorario l')
            ->innerJoin('l.horario h')
            ->where('h.id_curso = ?', array($id_curso))
            ->andWhere('l.id_asignatura = ?', array($id_asignatura))
            ->groupBy('l.id_actividad, l.num_grupo_actividad')
            ->orderBy('l.id_actividad, l.num_grupo_actividad');
    $grupos = $grupos_qry->execute();
    
    // Creamos la matriz vacía
    $matriz_info = array();
    
    // Devolver también las cabeceras con los grupos
    $header = array();
    $arraygrupos = array();
    
    // Recorremos todos los grupos de la asignatura, de forma que en cada iteración se consiga una columna de la matriz resultado.
    // La columna tendrá {num_semanas} filas y contendrá en cada casilla el número de horas impartidas en la semana correspondiente
    foreach($grupos as $key => $grupo)
    {
        //Obtenemos el nombre de la actividad para la cabecera
        $actividad = Doctrine::getTable('Actividad')->find($grupo->id_actividad);
        
        // Creamos un array de 5 ceros que contendrá las horas semanales de la actividad, y el día de la semana en la que se imparte
        $horas_semana = array_fill(0, 5, 0);
        $x = count($matriz_producto);
        // Este array será la columna que se insertará en la matriz resultante
        $resultado_columna = array_fill(0, count($matriz_producto), 0);
        
        // Si es teoría buscamos los horarios de las primeras semanas, las de solo teoría
        if($grupo->id_actividad == 1){
            for($i = 1; $i <= $curso->num_semanas_teoria; $i++){
                // Por cada semana creamos un array con las horas que se impartirán cada día esa semana.
                $horas_primeras_semanas = array_fill(0, 5, 0);
                $lineas = Doctrine_Query::create()
                        ->select('l.slot_minimo, l.dia_semana')
                        ->from('LineaHorario l')
                        ->innerJoin('l.horario h')
                        ->where('h.id_curso = ?', array($id_curso))
                        ->andWhere('l.id_actividad = ?', array($grupo->id_actividad))
                        ->andWhere('l.num_grupo_actividad = ?', array($grupo->num_grupo_actividad))
                        ->andWhere('l.id_asignatura = ?', array($grupo->id_asignatura))
                        ->andWhere('h.num_semana = ?', array($i))
                        ->andWhere('l.hora_inicial IS NOT NULL')
                        ->execute();
                // Recorremos las líneas y acumulamos las horas en el día correspondiente
                foreach($lineas as $linea){
                    $horas_primeras_semanas[$linea->dia_semana] += $linea->slot_minimo;
                }
                
                // Cogemos la fila correspondiente a la matriz producto y obtenemos los valores (1 o 0) para cada día de la semana
                $tmp = array();
                foreach($matriz_producto[$i-1] as $dia => $valor){
                    // Lo guardamos en un array temporal y sumamos, obtendremos el número de horas impartidas en esa semana
                    $tmp[$dia] = $valor * $horas_primeras_semanas[$dia];
                }
                $resultado_columna[$i-1] = array_sum($tmp);
            }
        }
        
        // La funcionalidad a partir de aquí es la misma que hace un momento pero para todo lo demás (Se podría simplificar y no repetir código pero ya está así)
        $lineas = Doctrine_Query::create()
                ->select('l.slot_minimo, l.dia_semana')
                ->from('LineaHorario l')
                ->innerJoin('l.horario h')
                ->where('l.id_actividad = ?', array($grupo->id_actividad))
                ->andWhere('h.id_curso = ?', array($id_curso))
                ->andWhere('l.num_grupo_actividad = ?', array($grupo->num_grupo_actividad))
                ->andWhere('l.id_asignatura = ?', array($grupo->id_asignatura))
                ->andWhere('h.num_semana = ?', array($curso->num_semanas_teoria+1))
                ->andWhere('l.hora_inicial IS NOT NULL')
                ->execute();
        
        // Nos vamos al plan docente para ver si la actividad va en semanas alternas
        $planactividad = Doctrine_Query::create()
            ->select('c.*')
            ->from('PlanActividad c')
            ->innerJoin('c.plandocente p')
            ->where('p.id_asignatura = ?', array($grupo->id_asignatura))
            ->andWhere('p.id_curso = ?', array($id_curso))
            ->andWhere('c.id_actividad = ?', array($grupo->id_actividad))
            ->execute();
        $alternas = $planactividad[0]->alternas;
        if($alternas){
            $header[] = $actividad->descripcion . " " . $grupo->num_grupo_actividad*2-1;
            $header[] = $actividad->descripcion . " " . $grupo->num_grupo_actividad*2;
        }else{
            $header[] = $actividad->descripcion . " " . $grupo->num_grupo_actividad;
        }
        foreach($lineas as $linea)
        {
            $horas_semana[$linea->dia_semana] += $linea->slot_minimo;
        }
        
        foreach($matriz_producto as $semana => $dias){
            if($semana >= $curso->num_semanas_teoria){
                foreach($dias as $dia => $valor){
                    if($alternas){
                        if($semana%2 == 0){
                            $tmp1[$dia] = $valor * $horas_semana[$dia];
                            $tmp2[$dia] = 0;
                        }else{
                            $tmp1[$dia] = 0;
                            $tmp2[$dia] = $valor * $horas_semana[$dia];
                        }
                        
                    }else{
                        $tmp[$dia] = $valor * $horas_semana[$dia];
                    }
                }
                if($alternas){
                    $resultado_columna_alternas1[$semana] = array_sum($tmp1);
                    $resultado_columna_alternas2[$semana] = array_sum($tmp2);
                }else{
                    $resultado_columna[$semana] = array_sum($tmp);
                }
            }
        }
        if($alternas)
        {
            $matriz_info[] = $resultado_columna_alternas1;
            $matriz_info[] = $resultado_columna_alternas2;
        }else{
            $matriz_info[] = $resultado_columna;
        }
        if($alternas)
        {
            $arraygrupos[] = array($grupo->id_actividad, $grupo->num_grupo_actividad*2-1);
            $arraygrupos[] = array($grupo->id_actividad, $grupo->num_grupo_actividad*2);
        }else{
            $arraygrupos[] = array($grupo->id_actividad, $grupo->num_grupo_actividad);
        }
    }
    
    return array($header, $arraygrupos, $matriz_info);
}