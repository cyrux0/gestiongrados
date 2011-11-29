<?php
class MY_Form_validation extends CI_Form_validation{
    
    function __construct(){
        parent::__construct();
    }
  
    function unique($value, $params){
        $CI =& get_instance();
        $current_id = $CI->uri->segment(3);
        $CI->form_validation->set_message('unique', 'The %s is already being used.');
        list($model, $field) = explode('.', $params, 2);
        $where = 'm.' . $field . ' = "' . $value . '"';
        if($current_id){
            $where = $where . ' AND ' . 'm.id != ' . $current_id;
        }
        
        $q = Doctrine_Query::create()
            ->select('m.id')->from($model . ' m')->where($where)->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        
        if($q->execute()){
            return false;
        }else{
            return true;
        }
    }
    function alpha_ext($str){
        $str = strtolower($this->CI->config->item('charset')) != 'utf-8' ? utf8_encode($str) : $str;
                $CI =& get_instance();
        $CI->form_validation->set_message('alpha_ext', 'El campo %s solo debería contener caracteres alfabéticos.');
        return (bool) (preg_match("/^[[:alpha:]- ÀÁÂÃÄÅĀĄĂÆÇĆČĈĊĎĐÈÉÊËĒĘĚĔĖĜĞĠĢĤĦÌÍÎÏĪĨĬĮİĲĴĶŁĽĹĻĿÑŃŇŅŊÒÓÔÕÖØŌŐŎŒŔŘŖŚŠŞŜȘŤŢŦȚÙÚÛÜŪŮŰŬŨŲŴÝŶŸŹŽŻàáâãäåæçèéêëìíîïñòóôõöøùúûüýÿœšß_.]+$/", $str));
    }
}
