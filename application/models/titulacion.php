<?php

/**
 * @Entity
 * @Table(name="titulaciones")
 */
class Titulacion extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->hasColumn('id_titulacion', 'integer');
    $this->hasColumn('nombre', 'string', 255);
    $this->hasColumn('codigo', 'string', 4);
    $this->hasColumn('creditos', 'integer');
  }
  
  

  /**
   * @Id
   * @Column(type="integer", nullable=false)
   * @GeneratedValue(strategy="AUTO")
   */
  private $id_titulacion;
  
  /**
   * @Column(type="string", length=200, nullable=false, unique=true)
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

