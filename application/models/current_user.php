<?php

class Current_User{
    
    private static $user;
    
    private function __construct(){}
    
    public static function user(){
        if(!isset(self::$user)){
            $CI =& get_instance();
            
            if(!$user_id = $CI->session->userdata('user_id')){
                return FALSE;
            }
            
            if(!$u = Doctrine::getTable('User')->find($user_id)){
                return FALSE;
            }
            
            self::$user = $u;
        }
        
        return self::$user;
    }
    
    public static function login($email, $password){
        if($u = Doctrine::getTable('User')->findOneByEmail($email) and $u->isAuthenticated($password)){
            self::$user = $u;
            $CI =& get_instance();
            $CI->session->set_userdata('user_id', $u->id);
            
            return TRUE;
        }else
            return FALSE;
    }
    
    public static function logged_in($security_level = -1){
        $u = self::user();
        return ($u and ($security_level == -1 or $u->level == $security_level) ) ? TRUE : FALSE;
    }
    
    
    public function __clone(){
        trigger_error('Clone is not allowed', E_USER_ERROR);
    }
    
} 