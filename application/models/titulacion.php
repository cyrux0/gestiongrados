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
  
  private $em;
  private $doctrine;

  public function __construct(){
    parent::__construct();
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
    $pruebas = new Doctrine;
    $pruebas->em->persist($this);
    $pruebas->em->flush();
  }
/*   public function list_all(){ */
/*     $query = $this->db->get('titulaciones'); */
/*     return $query->result(); */
/*   } */

/*   public function insert_new(){ */

/*     //Se usa _POST hay que cambiar eso por lo correspondiente */
/*     $this->nombre = $_POST['nombre']; */
/*     $this->creditos = $_POST['creditos']; */
/*     $this->codigo = $_POST['codigo']; */
    
/*     $this->db->insert('titulaciones',$this); */
/*   } */

/*   public function delete_elem($id){ */
/*     $this->db->delete('titulaciones', array('id_titulacion'=>$id)); */
/*   } */
  
/*   public function find($id){ */
/*     $query = $this->db->get_where('titulaciones', array('id_titulacion'=>$id), 1); */
/*     return $query->row(); */
/*   } */

/*   public function update($id){ */
/*     $data['codigo'] = $_POST['codigo']; */
/*     $data['nombre'] = $_POST['nombre']; */
/*     $data['creditos'] = $_POST['creditos']; */
    
/*     $this->db->where('id_titulacion', $id); */
/*     $this->db->update('titulaciones', $data); */
/*   } */

}

