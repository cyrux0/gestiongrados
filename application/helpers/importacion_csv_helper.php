<?php
function parse_csv_plandocente($filename, $process = false){
    $file = fopen($filename, 'rb');
    $data = fgetcsv($file, 0, ',');
    if(!$data){
        throw new Exception("Error en la línea 1: Los campos son incorrectos");
        return false;
    }
    $fields = array();
    foreach($data as $field)
        $fields[] = trim($field);
    $row = 2;

    // fields = asignatura | actividad | horas(totales) | grupos | horas_semanales | alternas | id_curso
    while(($data = fgetcsv($file, 0, ',')) != false){
        if(count($fields) != count($data)){
            throw new Exception("Error en la línea $row: número incorrecto de valores");
            return false;
        }
        $planactividad = new PlanActividad;
        
        $datarow = array_combine($fields, $data);
        if(!isset($datarow['id_asignatura'])){
            throw new Exception("Error en la línea $row: falta el identificador de la asignatura");
        }else{
            $plandocente = Doctrine_Query::create()
                            ->select("p.*")
                            ->from('PlanDocente p')
                            ->where('id_asignatura = ? AND id_curso = ?', array($datarow['id_asignatura'], $datarow['id_curso']))
                            ->execute();
            if(!$plandocente->count()){
                $plandocente = new PlanDocente();
                $plandocente->id_asignatura = $datarow['id_asignatura'];
                $plandocente->id_curso = $datarow['id_curso'];
                if(!$plandocente->isValid()){

                    throw new Exception("Error en la línea $row | " . $plandocente->getErrorStackAsString());
                }else{    
                    $plandocente->save();    
                }
            }else{
                $plandocente = $plandocente->getFirst();
            }
            $planactividad->id_plandocente = $plandocente->id;
        }
        $planactividad->fromArray($datarow);
        if(!$planactividad->isValid()){
            throw new Exception("Error en la línea $row | " . $planactividad->getErrorStackAsString());
        }else{           
            if($process){
                $planactividad->save();
            }
        }
        $row ++;
    }
    return $row;
}

function parse_csv_titulacion($filename, $process)
{

    $file = fopen($filename, 'rb');
    $data = fgetcsv($file, 0, ',');
    if(!$data){
        throw new Exception("Error en la línea 1: Los campos son incorrectos");
        return false;
    }
    
    $fields = array();
    foreach($data as $field)
        $fields[] = trim($field);
    $row = 2;
    
    $required_fields = array('codigo', 'nombre', 'creditos', 'num_cursos');
    
    if(count(array_diff($required_fields, $fields)) != 0)
    {
        throw new Exception("Error: Faltan campos");
    }
    
    while(($data = fgetcsv($file, 0, ',')) != false){
        if(count($fields) != count($data)){
            throw new Exception("Error en la línea $row: número incorrecto de valores");
            return false;
        }
        $titulacion = new Titulacion;
        
        $datarow = array_combine($fields, $data);
        $titulacion->fromArray($datarow);
        
        if(!$titulacion->isValid()){
            throw new Exception("Error en la línea $row | " . $asignatura->getErrorStackAsString());
        }else{           
            if($process){
                $titulacion->save();
            }
        }
        $row ++;
    }
    return $row;
}

function parse_csv_asignatura($filename, $process)
{
    $file = fopen($filename, 'rb');
    $data = fgetcsv($file, 0, ',');
    if(!$data){
        throw new Exception("Error en la línea 1: Los campos son incorrectos");
        return false;
    }
    
    $fields = array();
    foreach($data as $field)
        $fields[] = trim($field);
    $row = 2;
    
    $required_fields = array('codigo', 'nombre', 'abreviatura', 'creditos', 'materia', 'departamento', 'curso', 'semestre', 'titulacion_id');
    
    if(count(array_diff($required_fields, $fields)) != 0)
    {
        throw new Exception("Error: Faltan campos");
    }
    
    while(($data = fgetcsv($file, 0, ',')) != false){
        if(count($fields) != count($data)){
            throw new Exception("Error en la línea $row: número incorrecto de valores");
            return false;
        }
        $asignatura = new Asignatura;
        
        $datarow = array_combine($fields, $data);
        $asignatura->fromArray($datarow);
        
        if(!$asignatura->isValid()){
            throw new Exception("Error en la línea $row | " . $asignatura->getErrorStackAsString());
        }else{           
            if($process){
                $titulacion->save();
            }
        }
        $row ++;
    }
    return $row;
}

function export_calendar_csv($id_curso)
{
    $CI = & get_instance();
    $CI->load->helper('calendar_helper');
    
    $matriz_semestre_1 = matriz_producto_calendario($id_curso, "primero");
    $matriz_semestre_2 = matriz_producto_calendario($id_curso, "segundo");
    $CI->load->helper('file');
    //if(!write_file('./downloads/temp.csv', 'asd'))
    
    ;
    $file = fopen('./application/downloads/temp.csv', 'w');
    fputcsv($file, array('Semestre 1'), ',');
    
    foreach($matriz_semestre_1 as $num_semana => $array_semana)
    {
        $array_cruz = array();
        foreach($array_semana as $dia)
        {
            $array_cruz[] = $dia ? '':'X';
        }
        fputcsv($file, $array_cruz, ',');
    }
    
    fputcsv($file, array('Semestre 2'), ',');

    foreach($matriz_semestre_2 as $num_semana => $array_semana)
    {
        $array_cruz = array();
        foreach($array_semana as $dia)
        {
            $array_cruz[] = $dia ? '':'X';
        }
        fputcsv($file, $array_cruz, ',');
    }
    
    
}

function exportar_horario($id_horario)
{
    $horario = Doctrine::getTable('Horario')->find($id_horario);
    $id_curso = $horario->id_curso;
    $hora_inicial = $horario->curso->hora_inicial;
    $hora_final = $horario->curso->hora_final;
    $slot_minimo_minutos = $horario->curso->slot_minimo * 60;

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
                ->select('l.*, a.abreviatura, c.identificador')
                ->from('LineaHorario l')
                ->innerJoin('l.asignatura a')
                ->innerJoin('l.actividad c')
                ->where('l.id_horario = ?', array($id_horario))
                ->andWhere('l.dia_semana = ?', array($dia_semana))
                ->andWhere('l.hora_inicial IS NOT NULL')
                ->execute();

        foreach($lineas as $linea)
        {
            $hora_inicial_linea = date_create_from_format('H:i:s', $linea->hora_inicial);
            $hora_final_linea = date_create_from_format('H:i:s', $linea->hora_final);
            $slot_linea = $linea->slot_minimo*60;
            $nombre = $linea->asignatura->abreviatura . " " . $linea->actividad->identificador . $linea->num_grupo_actividad;
            for(; $hora_inicial_linea->getTimestamp() < $hora_final_linea->getTimestamp(); $hora_inicial_linea->add($interval))
            {
                $matriz_horario[$hora_inicial_linea->format("H:i")][$dia_semana] .= $matriz_horario[$hora_inicial_linea->format("H:i")][$dia_semana] != ''? '|':'';
                $matriz_horario[$hora_inicial_linea->format("H:i")][$dia_semana] .= $nombre;
            }
        }
    }        
    $file = fopen('./application/downloads/temp.csv', 'w');
    
    fputcsv($file, array('','L', 'M', 'X','J','V'), ',');
    
    foreach($matriz_horario as $key => $array_asigs){
        array_unshift($array_asigs, $key);
        fputcsv($file, $array_asigs, ',');
    }
}
?>
