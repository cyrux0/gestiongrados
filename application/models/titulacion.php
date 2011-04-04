<?php

/**
 * @Entity
 * @Table(name="titulaciones")
 */
class Titulacion
{

  /**
   * @Id
   * @Column(type="integer", nullable=false)
   * @GeneratedValue(strategy="AUTO")
   */
  private $id_titulacion;
  
  /**
   * @Column(type="string", length=20, nullable=false)
   */
  private $nombre = '';

  /**
   * @Column(type="string", length=4, nullable=false, unique=true)
   */
  private $codigo = '';

  /**
   * @Column(type="integer", nullable=false)
   */
  private $creditos = '';
  
  private $doctrine ;
  
  public function __construct(){
    $this->doctrine = new Doctrine;
  }


  public function __get($attr)
  {
    if(isset($this->$attr))
      return $this->$attr;
    else
      echo "errorget ".$attr; //Lanzar aquí una excepción;
  }

  public function __set($attr, $value){
    if(isset($this->$attr))
      $this->$attr = $value;
    else
      echo "errorset ".$attr; //Lanzar aquí una excepción;
  }

  public function save(){
    $this->doctrine->em->persist($this);
    $this->doctrine->em->flush();
  }
  
}

