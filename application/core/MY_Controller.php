<?

class MY_Controller extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    //Usar como filtro
    public function authenticate($security_level){
        if($u = Current_User::user() and $u->security_level == $security_level){
            return true;
        }else{
            $this->access_denied();
            return false;
        }
    }    
    protected function access_denied(){
        $this->session->sess_destroy();
        $this->session->set_flashdata('notices', 'Access denied, please log in to continue.');
        redirect('login');
    }
    
    protected function doctrine_validation($value, $field){
        $this->modelObject->isValid();
        $error_stack = $this->modelObject->getErrorStack();
        $errors = $error_stack->get($field);
        if(count($errors) > 0)
        {
            foreach($errors as $error){
                $this->form_validation->set_message('_doctrine_validation', $error);
            }
            return FALSE;
        }
        else
        {
            return TRUE;
        }
        
    }
    
    protected function _filter($actions, $callback, $callback_args){
        if(in_array($this->router->method, $actions)){
            call_user_func($callback, $callback_args);
        }
    }
}
