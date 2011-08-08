<?php

class Cursos extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->layout = '';
        $this->cursos_table = Doctrine::getTable('Curso');
        
    }

    public function add(){
        $curso = new Curso();
        $action = 'cursos/create';
        $this->load->view('cursos/add', array('page_title' => 'Nuevo Curso', 'data' => array('curso' => $curso, 'action' => $action)));
    }
    
    public function create(){
        $curso = new Curso();
        $curso->fromArray($this->input->post());
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_inicio_semestre1'));
        $curso->inicio_semestre1 = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_fin_semestre1'));
        $curso->fin_semestre1 = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_inicio_semestre2'));
        $curso->inicio_semestre2 = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_fin_semestre2'));
        $curso->fin_semestre2 = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_inicio_examenes_enero'));
        $curso->inicio_examenes_enero = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_fin_examenes_enero'));
        $curso->fin_examenes_enero = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_inicio_examenes_junio'));
        $curso->inicio_examenes_junio = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_fin_examenes_junio'));
        $curso->fin_examenes_junio = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_inicio_examenes_sept'));
        $curso->inicio_examenes_sept = $fecha->format('Y-m-d');
        $fecha = DateTime::createFromFormat("d/m/Y", $this->input->post('fecha_fin_examenes_sept'));
        $curso->fin_examenes_sept = $fecha->format('Y-m-d');        
        $diferencia1 = date_diff(date_create($curso->inicio_semestre1), date_create($curso->fin_semestre1));
        $curso->num_semanas_semestre1 = $diferencia1->d / 7;
        $diferencia2 = date_diff(date_create($curso->inicio_semestre2), date_create($curso->fin_semestre2));
        $curso->num_semanas_semestre2 = $diferencia2->d / 7;
        $curso->save();
        redirect('cursos/index');
    }
    
    public function edit($id){
        $curso = $this->cursos_table->find($id);
        $action = 'cursos/update/' . $id;
        $this->load->view('cursos/edit', array('page_title' => 'Editar Curso', 'data' => array('curso' => $curso, 'action' => $action)));
    }
    
    public function update($id){
        $curso = $this->cursos_table->find($id);
        $curso->fromArray($this->input->post());
        list($day, $month, $year) = explode('/', $this->input->post('inicio_semestre1'));
        $curso->inicio_semestre1 = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode('/', $this->input->post('inicio_semestre2'));
        $curso->inicio_semestre2 = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode('/', $this->input->post('fin_semestre1'));
        $curso->fin_semestre1 = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode('/', $this->input->post('fin_semestre2'));
        $curso->fin_semestre2 = $year . '-' . $month . '-' . $day;        
        list($day, $month, $year) = explode('/', $this->input->post('inicio_examenes_enero'));
        $curso->inicio_examenes_enero = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode('/', $this->input->post('fin_examenes_enero'));
        $curso->fin_examenes_enero = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode('/', $this->input->post('inicio_examenes_junio'));
        $curso->inicio_examenes_junio = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode('/', $this->input->post('fin_examenes_junio'));
        $curso->fin_examenes_junio = $year . '-' . $month . '-' . $day;                        
        list($day, $month, $year) = explode('/', $this->input->post('inicio_examenes_sept'));
        $curso->inicio_examenes_sept = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode('/', $this->input->post('fin_examenes_sept'));
        $curso->fin_examenes_sept = $year . '-' . $month . '-' . $day;
        $curso->save();
        redirect('cursos/index');
    }

    public function index(){
        $cursos = $this->cursos_table->findAll();
        $this->load->view('cursos/index', array('cursos' => $cursos));
    }

    public function delete($id){
        $cursos = $this->cursos_table->find($id);
        $cursos->eventos->delete();
        $cursos->delete();
        redirect('cursos/index');
    }
}


/* End of file: cursos.php */
/* Location: ./application/controllers/cursos.php */ 
