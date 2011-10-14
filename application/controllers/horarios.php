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
    
    public function add_grupo($id_titulacion, $id_curso, $curso_titulacion, $num_grupo){
        $horario = new Horario;
        $horario->id_curso = $id_curso;
        $horario->id_titulacion = $id_titulacion;
        $horario->num_curso_titulacion = $curso_titulacion;
        $horario->num_grupo_titulacion = $num_grupo;
        
        $query_asignaturas = Doctrine_Query::create()
                            ->select('a.id, p.id, c.id, c.horas, c.grupos, c.alternas, c.actividad')
                            ->from('Asignatura a')
                            ->innerJoin('a.PlanesDocentes p')
                            ->innerJoin('p.planactividades c')
                            ->where("a.curso = ?", $curso_titulacion)
                            ->andWhere("a.titulacion_id = ?", $id_titulacion)
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
        redirect('/');
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

    public function edit_teoria($id_tipo){
        $horario = Doctrine::getTable("Horario")->find($id_tipo);
        
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
}
