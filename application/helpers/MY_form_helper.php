<?php
function set_value($field = '', $default = '')
{
    if ( ! isset($_POST[$field]))
    {
        return $default;
    }

    return form_prep($_POST[$field], $field);
}