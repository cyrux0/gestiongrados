<?php
class Users extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->users_table = Doctrine::getTable('User');
        $this->layout = '';
        $this->notices = '';
        $this->alerts = '';
        $this->_filter(array('delete'), array($this, 'authenticate'), 0); // Sólo admins
   }

    function add(){
        $user = new User;
        $titulaciones = Doctrine::getTable('Titulacion')->findAll();
        $array_titulaciones = array();
        
        foreach($titulaciones as $titulacion)
        {
            $array_titulaciones[$titulacion->id] = $titulacion->nombre;
        }
        
        $this->load->view('users/add', array('user' => $user, 'titulaciones' => $array_titulaciones, 'action' => 'users/create'));
    }
    
    
    function create(){
        $user = new User;

        $user->fromArray($this->input->post());
        /* if(!$user->isValid() or $this->input->post('password')!=$this->input->post('password_confirmation')){ // Esto hay que cambiarlo por validaciones de codeigniter.
            $this->alerts = $user->getErrorStackAsString();
            $this->add();
        }
         */
        $password = $user->generate_password();
        
        if($this->_submit_validate() == FALSE){
            $this->add();
            return; 
        }else{
            $this->load->library('phpmailer');
            $mail = new PHPMailer;
            $mail->SMTPAuth = true;
            $mail->Username = "danielsalazarrecio@gmail.com";
            $mail->Password = "T1tGoGvl4c10n";
            $mail->Host = "smtp.gmail.com";
            $mail->Mailer = "mail";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 25;
            $mail->From = 'info@uca.es';
            $mail->Encoding = "utf-8";
            $mail->FromName = 'Aplicación para la gestión de la planificación docente de la ESI (UCA)';
            $mail->AddAddress($user->email);
            $mail->IsHTML(true);
            $mail->Subject = 'Password para aplicación de gestión de grados';
            $mail->Body = "
            <p>
            El password para tu cuenta en la aplicación de gestión de grados de la ESI es: $password
            Debes iniciar sesión con tu cuenta de email y el password indicado aquí. Para iniciar sesión ve a:
                    <a href=\"". site_url('login') . "\" >Login</a>
            </p>
            ";
            if($mail->send()){
                $user->save();
                redirect('welcome');
            }else{
                echo "email not sended";
            }
            
        }
    }
    
    function edit(){
        $user = Current_User::user();
        $this->load->view('users/add', array('user' => $user, 'action' => 'users/update/' . $user->id));
    }
    
    function update($id){
        $user = Current_User::user();
        $user->fromArray($this->input->post());
        
        /*if(!$user->isValid() or $this->input->post('password')!=$this->input->post('password_confirmation')){
            $this->alerts = $user->getErrorStackAsString();
            $this->add();
        }
         * */
         if($this->_submit_validate() == FALSE){
             $this->edit();
             return;
         }
         else{
            $user->save();
            redirect('welcome');
        }
    }
    
    function delete($id){
        $user = $this->users_table->find($id);
        $user->delete();
    }
    
    private function _submit_validate(){
        $this->form_validation->set_rules('email', 'Email', 'required|minlength[4]|maxlength[16]');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|minlength[4]|maxlength[50]|alpha_ext');
        $this->form_validation->set_rules('apellidos', 'Apellidos', 'required|minlength[4]|maxlength[50]|alpha_ext');
        $this->form_validation->set_rules('DNI', 'DNI', 'required|exact_length[9]');
        return $this->form_validation->run();
    }
}

/* Fin del archivo users.php */
