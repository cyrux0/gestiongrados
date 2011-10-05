<?php


class Horarios extends MY_Controller{
    
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
                            ->select('a.id, a.curso, p.*')
                            ->from('Asignatura a')
                            ->innerJoin('a.PlanesDocentes p')
                            ->where("a.curso = ?", $curso_titulacion);
        $asignaturas = $query_asignaturas->execute();
        
        $curso = Doctrine::getTable('Cursos')->find($id_curso);
        foreach($asignaturas as $asignatura){
            $horas_teoria = $asignatura->horas_teoria / $curso->slot_minimo;
            for($i = 0; $i < $horas_teoria; $i++){
                $linea_horario = new LineaHorario;
                $linea_horario->actividad = 1;
                $linea_horario->duracion = $curso->slot_minimo;
                $horario->lineashorario[] = $linea_horario;
            }
            if($asignatura->horas_problemas){
                $linea_horario = new LineaHorario;
                $linea_horario->actividad = 2;
                $linea_horario->duracion = $asignatura->horas_problemas;
                $horario->lineashorario[] = $linea_horario;
            }
            if($asignatura->horas_informatica){
                $linea_horario = new LineaHorario;
                $linea_horario->actividad = 3;
                $linea_horario->duracion = $asignatura->horas_informatica;
                $horario->lineashorario[] = $linea_horario;
                //Por cada grupo de la actividad habría que crear una entrada, dividiendo entre el número de grupos de teoría
                
            }
        }
        
        $horario->lineashorario[] = 
    }
}
