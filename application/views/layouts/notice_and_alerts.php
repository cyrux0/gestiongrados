<?php
if(isset($notice)){
    echo '<p class="notice">' . $notice . '</p>';
}else if($this -> session -> flashdata('notice')) {
    echo '<p class="notice">' . $this -> session -> flashdata('notice') . '</p>';
}

if(isset($alert)){
    echo '<p class="alert">' . $alert . '</p>';
}else if($this -> session -> flashdata('alert')) {
    echo '<p class="alert">' . $this -> session -> flashdata('alert') . '</p>';
}
echo validation_errors('<p class="alert">', '</p>');
