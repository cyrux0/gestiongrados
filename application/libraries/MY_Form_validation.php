<?php
class MY_Form_validation extends CI_Form_validation{
    
    function __construct(){
        parent::__construct();
    }
    function unique($value, $field){
        $CI =& get_instance();
        $CI->form_validation->set_message('unique', 'The value given for %s already exists in database');
        list($table, $column) = explode('.', $field, 2);
        $c = $column[0];
        $q = Doctrine_Query::create()->select('*')->from(ucwords($table) . ' ' . $table[0])->where($table[0] . '.' . $column  . ' = ' . '"' . $value . '"');
        $result = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return empty($result);
    }

    function alpha_ext($str){
        $str = strtolower($this->CI->config->item('charset')) != 'utf-8' ? utf8_encode($str) : $str;
                $CI =& get_instance();
        $CI->form_validation->set_message('alpha_ext', 'The Nombre field may only contain alphabetical characters.');
        return (bool) (preg_match("/^[[:alpha:]- ÀÁÂÃÄÅĀĄĂÆÇĆČĈĊĎĐÈÉÊËĒĘĚĔĖĜĞĠĢĤĦÌÍÎÏĪĨĬĮİĲĴĶŁĽĹĻĿÑŃŇŅŊÒÓÔÕÖØŌŐŎŒŔŘŖŚŠŞŜȘŤŢŦȚÙÚÛÜŪŮŰŬŨŲŴÝŶŸŹŽŻàáâãäåæçèéêëìíîïñòóôõöøùúûüýÿœšß_.]+$/", $str));
    }
}
