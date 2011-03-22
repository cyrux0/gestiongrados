<?php
abstract class ModelBase
{
  protected static function db(){
    return SPDO::singleton();
  }
}
?>
