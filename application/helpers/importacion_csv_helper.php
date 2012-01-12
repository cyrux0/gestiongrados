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
    list($fecha_inicial_sem1, $fecha_lunes_sem1, $fecha_final_sem1 ) = dias_iniciales($id_curso, 1, "primero");
    $fecha_inicial_sem1 = $fecha_lunes_sem1;
    $mes_actual = $fecha_inicial_sem1->format('m');
    fputcsv($file, array("Mes " . $fecha_inicial_sem1->format("n")));
    $interval = new DateInterval("P1D");
    foreach($matriz_semestre_1 as $num_semana => $array_semana)
    {
        $array_cruz = array();
        $array_semana[] = 0;
        $array_semana[] = 0;
        foreach($array_semana as $dia)
        {
            $array_cruz[] = $dia ? $fecha_inicial_sem1->format('d'):'X';
            $fecha_inicial_sem1->add($interval);
            if($mes_actual != $fecha_inicial_sem1->format("m")){
                $mes_actual = $fecha_inicial_sem1->format("m");
                foreach(range($fecha_inicial_sem1->format("N"), 7) as $dia_semana)
                {
                    $array_cruz[] = "-";
                }
                fputcsv($file, $array_cruz, ",");
                fputcsv($file, array("Mes " . $fecha_inicial_sem1->format("n")));
                $array_cruz = array();
                foreach(range(1, $fecha_inicial_sem1->format("N")-1) as $dia_semana)
                {
                    $array_cruz[] = "-";
                }
            }
        }
        fputcsv($file, $array_cruz, ',');
    }
    
    fputcsv($file, array('Semestre 2'), ',');
    list($fecha_inicial_sem1, $fecha_lunes_sem1, $fecha_final_sem1 ) = dias_iniciales($id_curso, 1, "segundo");
    $fecha_inicial_sem1 = $fecha_lunes_sem1;
    $mes_actual = $fecha_inicial_sem1->format('m');
    fputcsv($file, array("Mes " . $fecha_inicial_sem1->format("n")));
    foreach($matriz_semestre_2 as $num_semana => $array_semana)
    {
$array_cruz = array();
        $array_semana[] = 0;
        $array_semana[] = 0;
        foreach($array_semana as $dia)
        {
            $array_cruz[] = $dia ? $fecha_inicial_sem1->format('d'):'X';
            $fecha_inicial_sem1->add($interval);
            if($mes_actual != $fecha_inicial_sem1->format("m")){
                $mes_actual = $fecha_inicial_sem1->format("m");
                foreach(range($fecha_inicial_sem1->format("N"), 7) as $dia_semana)
                {
                    $array_cruz[] = "-";
                }
                fputcsv($file, $array_cruz, ",");
                fputcsv($file, array("Mes " . $fecha_inicial_sem1->format("n")));
                $array_cruz = array();
                foreach(range(1, $fecha_inicial_sem1->format("N")-1) as $dia_semana)
                {
                    $array_cruz[] = "-";
                }
            }
        }
        fputcsv($file, $array_cruz, ',');
    }
    
    
}

function exportador_csv($filename, $matriz)
{
    $file = fopen($filename, 'w');
    
    foreach($matriz as $array_values){
        fputcsv($file, $array_values, ',');
    }
}
?>
