<?php

/**
 * Clase para la gestión de los planes docentes
 * 
 * @package Controller
 */
class PlanesDocentes extends MY_Controller{
  function __construct(){
    parent::__construct();
    $this->globales_table = Doctrine::getTable('PlanDocente');
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
    $this->cursos_table = Doctrine::getTable('Curso');
    $this->layout = '';
    $this->alerts = '';
    $this->notices = '';
    $this->_filter(array('add_carga', 'create', 'edit', 'update', 'delete', 'load', 'upload_file', 'make_upload', 'informe_asignatura'), array($this, 'authenticate'), 1); // Sólo al planner 
  }

   /**
    * Acción para mostrar el formulario de añadir una carga
    * 
    * @param integer $id_asignatura Identificador de la asignatura
    * @param integer $id_curso Identificador del curso
    */
   public function add_carga($id_asignatura, $id_curso = ''){
        if(!$id_curso) redirect('cursos/select_curso/planesdocentes/add_carga/' . $id_asignatura );
        $query_plan = Doctrine_Query::create()
                ->select('*')
                ->from('PlanDocente p')
                ->where('p.id_curso = ?', $id_curso)
                ->andWhere('p.id_asignatura = ?', $id_asignatura)
                ->execute();
        $asignatura = $this->asignaturas_table->find($id_asignatura);
        if($query_plan->count()){
            $this->session->set_flashdata('alerts', 'Plan docente ya creado');
            redirect('titulaciones/show/' . $asignatura->titulacion_id . "/" . $id_curso);
        }else{
            $global = new PlanDocente;
            $global->id_asignatura = $id_asignatura;
            $global->id_curso = $id_curso;

            $action = 'planesdocentes/create/';
            $data['data'] = array('result' => $global, 'action' => $action);
            $data['nombre_asignatura'] = $asignatura->nombre;
            $data['page_title'] = 'Añadiendo carga global';
            $data['data']['curso_asignatura'] = $asignatura->curso;
            $data['data']['cursos_totales'] = Doctrine::getTable('Titulacion')->find($asignatura->titulacion_id)->num_cursos;
            $this->load->view('PlanDocente/add', $data);
        }
    }
    
    
    public function create(){
        $global = new PlanDocente;
        $global->id_curso = $this->input->post('id_curso');
        $global->id_asignatura = $this->input->post('id_asignatura');
        foreach($this->input->post('horas') as $key => $horas){
            $array_grupos = $this->input->post('grupos');
            $array_horas_semanales = $this->input->post('horas_semanales');
            $array_alternas = $this->input->post('alternas');              
            if($horas){
                $planactividad = new PlanActividad;
                $planactividad->horas = $horas;
                $planactividad->grupos = $array_grupos[$key];
                $planactividad->horas_semanales = $array_horas_semanales[$key];
                $planactividad->alternas = isset($array_alternas[$key])? 1:0;
                $planactividad->id_actividad = $key;             
                $global->planactividades[] = $planactividad;
            }
            
        }
        
        if($this->input->post('cursoscompartidos'))
        {
            foreach($this->input->post('cursoscompartidos') as $curso)
            {
                $cursocompartido = new CursoCompartido;
                $cursocompartido->num_curso_compartido = $curso['num_curso_compartido'];
                $global->cursoscompartidos[] = $cursocompartido;
            }
        }
        
        if($this->_submit_validate()===FALSE){
            $this->add_carga($this->input->post('id_asignatura'), $this->input->post('id_curso'));
        }else{
            $global->save();
            $this->notices = 'Asignatura añadida correctamente';
            $this->session->set_flashdata('notice', $this->notices);
            redirect('titulaciones/index');
        }

    }
    
    public function edit($id){
        $global = $this->globales_table->find($id);
        $asignatura = $this->asignaturas_table->find($global->id_asignatura);
        $action = '/planesdocentes/update/' . $id;
        $data['data'] = array('result' => $global, 'action' => $action);
        $data['nombre_asignatura'] = $asignatura->nombre;
        $data['page_title'] = 'Editando carga global';
        $data['data']['curso_asignatura'] = $asignatura->curso;
        $data['data']['cursos_totales'] = Doctrine::getTable('Titulacion')->find($asignatura->titulacion_id)->num_cursos;
        
        $this->load->view('PlanDocente/edit', $data);
    }

    public function update($id){
        $global = $this->globales_table->find($id);
        $global->fromArray($this->input->post());
        if($this->_submit_validate()==FALSE){
            $this->edit($id);
        }else{
            $global->save();
            $this->notices = 'Carga actualizada correctamente';
            $this->session->set_flashdata('notices', $this->notices);
            redirect('titulaciones/show/' . $global->Asignatura->titulacion_id);
        }
    }
/*
    public function load($asignatura_id, $curso_id){
        $q = Doctrine_Query::create()->select('p.*')->from('PlanDocente p')->where('p.curso_id = ' . $curso_id . ' AND p.asignatura_id = ' . $asignatura_id)->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $result = $q->execute();
        $global = new PlanDocente;
        $global->fromArray($result[0]);
        $cursos = $this->cursos_table->findAll();
        $options = array();
        foreach($cursos as $curso){
            $options[$curso->id] = date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y");
        }
        $action = 'planesdocentes/create/';
        $data['data'] = array('result' => $global, 'action' => $action);
        $data['nombre_asignatura'] = $this->asignaturas_table->find($asignatura_id)->nombre;
        $data['page_title'] = 'Añadiendo carga global';
        $data['options'] = $options;
        $this->load->view('PlanDocente/add', $data);
    }
*/
    public function upload_file(){
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv|xls';
		$config['max_size']	= '100';
        
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload()){
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('PlanDocente/from_file', $error);
        }else{
            $data = $this->upload->data();
            try{
                $this->load->helper('importacion_csv_helper');
                parse_csv_plandocente($data['full_path']);
                $rows = parse_csv_plandocente($data['full_path'], true);
                $this->load->view('plandocente/upload_success', array('rows' => $rows));
            }catch(Exception $e){
                $error = array('error' => $e->getMessage());
                $this->load->view('PlanDocente/from_file', $error);
            }
        }
    }
    
    public function make_upload(){
        $this->load->view('PlanDocente/from_file', array('action' => 'planesdocentes/upload_file'));
    }
    
    /**
     * Acción para generar informe de múltiples asignaturas pasadas por POST
     * 
     * @param integer $id_curso Identificador del curso
     */
    public function informe_asignatura($id_curso)
    {
        $this->load->helper('resumen_asignatura_helper');
        
        $this->load->helper('calendar_helper');
        $this->load->library('FPDF');
        
        define('FPDF_FONTPATH', $this->config->item('fonts_path'));
        $this->load->library('FPDF');
        $pdf = new FPDF('P', 'mm', 'A4');
        foreach($this->input->post('seleccionada') as $id_asignatura)
        {
            $asignatura = Doctrine::getTable('Asignatura')->find($id_asignatura);            
            // Obtenemos un array header con las cabeceras y otra array donde cada elemento es un array con las horas de cada semana de ese grupo.
            list($header, $arraygrupos, $horas) = resumen_asignatura($id_asignatura, $id_curso);
            $nombre_titulacion = utf8_decode($asignatura->Titulacion->nombre);
            $nombre_asignatura = utf8_decode($asignatura->nombre);
            $pdf = generar_pdf($nombre_titulacion, $nombre_asignatura, $id_asignatura, $id_curso, $header, $arraygrupos, $horas, $pdf);    
        }
        unset($this->layout);
        $pdf->Output();
    }
    
    public function delete($id)
    {
        $plan = Doctrine::getTable('PlanDocente')->find($id);
        $plan->planactividades->delete();
        $plan->cursoscompartidos->delete();
        $plan->delete();
    }
    
    public function compartir($id)
    {
        $plan_docente = Doctrine::getTable('PlanDocente')->find($id);
        $this->load->view("PlanDocente/compartir", array('action' => "planesdocentes/crear_compartidas/$plan_docente->id", 'result' => $plan_docente, 'curso_asignatura' => $plan_docente->Asignatura->curso, 'cursos_totales' => $plan_docente->Asignatura->Titulacion->num_cursos));
    }
    
    public function crear_compartidas($id)
    {
        if($this->input->post('cursoscompartidos'))
        {
            $plan_docente = Doctrine::getTable('PlanDocente')->find($id);
            $x = $this->input->post();
            foreach($this->input->post('cursoscompartidos') as $curso)
            {
                $cursocompartido = new CursoCompartido;
                $cursocompartido->num_curso_compartido = $curso['num_curso_compartido'];
                $plan_docente->cursoscompartidos[] = $cursocompartido;
            }
            $plan_docente->save();
        }
        
        redirect("titulaciones/show");
    }
        
    /**
     * Hace las validaciones del formulario de añadir planes docentes
     * @return Boolean
     */
    private function _submit_validate(){
        $this->form_validation->set_rules('horas[]', 'Horas', 'trim|is_natural');
        $this->form_validation->set_rules('grupos[]', 'Grupos', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales[]', 'Horas Semanales', 'trim|is_natural');
        
        return $this->form_validation->run();
    }
    
    
}

/* Fin del archivo planesdocentes.php */
