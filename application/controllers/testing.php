<?php

class Testing extends CI_Controller{
  
  public function getpath(){
    echo Doctrine_Core::getPath();
  }


}