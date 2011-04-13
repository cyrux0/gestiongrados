<?php


/**
 * @Entity
 * @Table(name="asignaturas")
 */
class Asignatura{
  
  /**
   * @Id
   * @Column(type="integer", nullable=false)
   * @GeneratedValue(strategy="AUTO")
   */
  private $id_asignatura;

  /**
   * @Column(type="string", length=200, nullable=false, unique=true)
   */
  private $nombre = '';
  
  /**
   * @Column(type="string", length=3, nullable=false, unique=true)
   */
  private $codigo = '';

  /**
   * @Column(type="integer", nullable=false)
   */
  private $creditos = '';

}
