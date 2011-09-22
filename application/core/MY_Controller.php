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
        redirect('logout');
        $this->session->set_flashdata('notices', 'Access denied, please log in to continue.');
        redirect('login');
    }
    
    
}
