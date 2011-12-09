<?php
if(isset($this->notices) and $this->notices != ''){
    echo '<p class="notice">' . $this->notices . '</p>';
}else if($this->session->flashdata('notices')){
	echo '<p class="notice">' . $this->session->flashdata('notices') . '</p>';
}

if(isset($this->alerts) and $this->alerts != ''){
    echo '<p class="errors">' . $this->alerts . '</p>';
}
if(validation_errors()){
    echo '<div class="errors">';
    echo validation_errors('<p class="alert">', '</p>');
    echo '</div>';
}