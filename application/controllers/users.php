<?php
class Users extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->users_table = Doctrine::getTable('User');
        $this->layout = '';
        $this->notices = '';
        $this->alerts = '';
        
    }

    function add(){
        $user = new User;
        $this->load->view('users/add', array('user' => $user, 'action' => 'users/create'));
    }
    
    function create(){
        $user = new User;
        $user->fromArray($this->input->post());
        if(!$user->isValid()){
            $this->alerts = $user->getErrorStackAsString();
            $this->add();
        }else{
            $user->save();
            redirect('welcome');
        }
    }
    
    function edit($id){
        
    }
    
    function update(){
        
    }
    
    function delete($id){
        
    }
}

/* Fin del archivo users.php */
