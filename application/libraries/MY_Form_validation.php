<?php
class MY_Form_validation extends CI_Form_validation{
    
    function __construct(){
        parent::__construct();
    }
    function unique($value, $field){
        $CI =& get_instance();
        $CI->form_validation->set_message('unique', 'The %s value exists in database');
        list($table, $column) = explode('.', $field, 2);
        $c = $column[0];
        $q = Doctrine_Query::create()->select('*')->from(ucwords($table) . ' ' . $table[0])->where($table[0] . '.' . $column  . ' = ' . '"' . $value . '"');
        $result = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return empty($result);
    }
}
