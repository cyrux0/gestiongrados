<?php

class PlanesDocentes extends MY_Controller{
  function __construct(){
    parent::__construct();
    $this->globales_table = Doctrine::getTable('PlanDocente');
    $this->asignaturas_table = Doctrine::getTable('Asignatura');
    $this->cursos_table = Doctrine::getTable('Curso');
    $this->layout = '';
    $this->alerts = '';
    $this->notices = '';
    $this->_filter(array('create', 'edit', 'update', 'delete', 'load'), array($this, 'authenticate'), 2); // Sólo al planner 
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
        
        if($this->_submit_validate()==FALSE){
            $this->add_carga($this->input->post('asignatura_id'));
        }else{
            $global->save();
            $this->notices = 'Asignatura añadida correctamente';
            $this->session->set_flashdata('notice', $this->notices);
            redirect('titulaciones/index');
        }

    }
    
    public function edit($id){
        $global = $this->globales_table->find($id);
        $action = '/planesdocentes/update/' . $id;
        $data['data'] = array('result' => $global, 'action' => $action);
        $data['nombre_asignatura'] = $this->asignaturas_table->find($global->asignatura_id)->nombre;
        $data['page_title'] = 'Editando carga global';
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
                $rows = $this->_parse_data($data['full_path'], true);
                $this->load->view('plandocente/upload_success', array('rows' => $rows));
            }catch(Exception $e){
                $error = array('error' => $e->getMessage());
                $this->load->view('PlanDocente/from_file', $error);
            }
        }
    }
    
    public function make_upload(){
        $this->load->view('PlanDocente/from_file', array('error' => ''));
    }
    
    
    public function informe_asignatura($id_asignatura, $id_curso)
    {
        $this->load->helper('resumen_asignatura_helper');
        $asignatura = Doctrine::getTable('Asignatura')->find($id_asignatura);
        $this->load->helper('calendar_helper');
        // Obtenemos un array header con las cabeceras y otra array donde cada elemento es un array con las horas de cada semana de ese grupo.
        list($header, $arraygrupos, $horas) = resumen_asignatura($id_asignatura, $id_curso);
        
        $horas_traspuesta = call_user_func_array('array_map',array_merge(array(NULL),$horas));
        $this->load->library('fpdf');
        
        // Creación de pdf y título
        define('FPDF_FONTPATH', $this->config->item('fonts_path'));
        unset($this->layout);
        $this->load->library('fpdf');
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', 'BU', 25);
        $pdf->Cell(0, 0, 'Informe de asignatura', 0, 1);
        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', 'B', 15);
        $nombre_titulacion = utf8_decode($asignatura->Titulacion->nombre);
        $nombre_asignatura = utf8_decode($asignatura->nombre);
        $pdf->Cell(0, 0, $nombre_titulacion, 0, 1);
        $pdf->Ln(7);
        $pdf->Cell(0, 0,'    '. $nombre_asignatura, 0, 1);
        $pdf->Ln(10);
        
        // Aquí debemos rellenar la planificación docente
        $planactividad = Doctrine_Query::create()
            ->select('c.*')
            ->from('PlanActividad c')
            ->innerJoin('c.plandocente p')
            ->where('p.id_asignatura = ?', array($id_asignatura))
            ->andWhere('p.id_curso = ?', array($id_curso))
            ->orderBy('c.id')
            ->execute();
        
        $pdf->SetFillColor(192,192,192);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        $anchototal = 40;
        // ancho, alto, texto, borde, linea de comienzo, align
        $pdf->Cell(40, 7, '', 1, 0, 'L');
        
        foreach($planactividad as $actividad)
        {
            $act_element = Doctrine::getTable('Actividad')->find($actividad->id_actividad);
            $pdf->Cell(25, 7, $act_element->identificador, 1, 0, 'L');
            
            $anchototal += 25;
        }
        $pdf->Ln();
        $pdf->Cell(40, 7, 'Planificado', 1, 0, 'L');
        $pdf->SetFont('');
        foreach($planactividad as $actividad)
        {
            $pdf->Cell(25, 7, $actividad->horas, 1, 0, 'L');
        }
        
        $pdf->Ln(20);

        // Cabecera
        $anchototal = 0;
        $pdf->Cell(15, 7, 'Sem.', 1, 0, 'C', true);
        for($i=0;$i<count($header);$i++){
            $element = utf8_decode($header[$i]);
            $pdf->Cell(35,7, $element,1,0,'C',true);
            $anchototal += 35;
        }
        $pdf->Ln();
        // Restauración de colores y fuentes
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Datos
        $fill = false;
        
        foreach($horas_traspuesta as $key => $row)
        {
            $pdf->Cell(15, 6, $key+1, 'LR', 0, 'L', $fill);
            foreach($row as $hora)
            {
                $pdf->Cell(35,6,$hora,'LR',0,'L',$fill);
            }
            $pdf->Ln();
            $fill = !$fill;
        }
        
        $pdf->Cell(15, 6, 'Total: ', 'LRT', 0, 'L', $fill);
        foreach($horas as $hora){
            $suma = array_sum($hora);
            $pdf->Cell(35, 6, $suma, 'LRT', 0, 'L', $fill);
        }
        $pdf->Ln();
        // Línea de cierre
        $pdf->Cell($anchototal+15,0,'','T');
        $pdf->Output();
    }
        
    private function _submit_validate(){
        $this->form_validation->set_rules('horas_teoria', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_teoria', 'trim|is_natural');
        $this->form_validation->set_rules('horas_problemas', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_problemas', 'trim|is_natural');
        $this->form_validation->set_rules('horas_informatica', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_informatica', 'trim|is_natural');
        $this->form_validation->set_rules('horas_lab', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_lab', 'trim|is_natural');
        $this->form_validation->set_rules('horas_campo', 'trim|is_natural');
        $this->form_validation->set_rules('grupos_campo', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_teoria', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_problemas', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_informatica', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_lab', 'trim|is_natural');
        $this->form_validation->set_rules('horas_semanales_campo', 'trim|is_natural');
        
        return $this->form_validation->run();
    }
}

/* Fin del archivo planesdocentes.php */
