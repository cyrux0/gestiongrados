<?php

function matriz_producto_calendario($id_curso, $semestre)
{
    $curso = Doctrine::getTable('Curso')->find($id_curso);
    
    if($semestre == "primero")
    {
        $num_semanas = $curso->num_semanas_semestre1;
    }
    else
    {
        $num_semanas = $curso->num_semanas_semestre2;
    }
    
    
    $matriz_producto = array_fill(0, $num_semanas, array_fill(0, 5, 1));
    
    $CI = & get_instance();
    $CI->load->helper('resumen_asignatura_helper');
    list($fecha_inicial, $fecha_lunes, $fecha_final) = dias_iniciales($id_curso, 1, $semestre);
    
    $eventos = Doctrine_Query::create()
                ->select("e.*")
                ->from("Evento e")
                ->where("e.curso_id = ?", $id_curso)
                ->andWhere('e.fecha_final < ?', $fecha_final->format('Y-m-d'))
                ->execute();
    
    for($i = 0; $i< intval($fecha_inicial->format("w"))-1; $i++)
    {
        $matriz_producto[0][$i] = 0;
    }
    
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
    
    for($i = intval($fecha_final->format("w")); $i<5; $i++)
    {
        $matriz_producto[$num_semanas][$i] = 0;
    }

    return $matriz_producto;
}

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
            ->where('l.id_asignatura = ?', array($id_asignatura))
            ->groupBy('l.id_actividad, l.num_grupo_actividad')
            ->orderBy('l.id_actividad, l.num_grupo_actividad');
    $grupos = $grupos_qry->execute();
    
    // Contamos para hallar el número de columnas que tendrá la matriz resultante
    $num_columnas = count($grupos);
    // Creamos la matriz vacía
    $matriz_info = array();
    
    // Devolver también las cabeceras con los grupos
    
    $header = array();
    
    // Recorremos todos los grupos de la asignatura, de forma que en cada iteración se consiga una columna de la matriz resultado.
    // La columna tendrá {num_semanas} filas y contendrá en cada casilla el número de horas impartidas en la semana correspondiente
    foreach($grupos as $key => $grupo)
    {
        //Obtenemos el nombre de la actividad para la cabecera
        $actividad = Doctrine::getTable('Actividad')->find($grupo->id_actividad);
        $header[] = $actividad->descripcion . $grupo->num_grupo_actividad;
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
                        ->where('l.id_actividad = ?', array($grupo->id_actividad))
                        ->andWhere('l.num_grupo_actividad = ?', array($grupo->num_grupo_actividad))
                        ->andWhere('h.num_semana = ?', array($i))
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
                ->andWhere('l.num_grupo_actividad = ?', array($grupo->num_grupo_actividad))
                ->andWhere('h.num_semana = ?', array($curso->num_semanas_teoria+1))
                ->andWhere('l.hora_inicial IS NOT NULL')
                ->execute();
        
        foreach($lineas as $linea)
        {
            $horas_semana[$linea->dia_semana] += $linea->slot_minimo;
        }
        
        foreach($matriz_producto as $semana => $dias){
            if($semana >= $curso->num_semanas_teoria){
                foreach($dias as $dia => $valor){
                    $tmp[$dia] = $valor * $horas_semana[$dia];
                }
                $resultado_columna[$semana] = array_sum($tmp);
            }
        }
        $matriz_info[] = $resultado_columna;
    }
    
    return array($header, $matriz_info);
}