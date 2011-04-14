<?php
/*
 *  $Id: Doctrine.php 7490 2010-03-29 19:53:27Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

require_once 'Doctrine/Core.php';

/**
 * This class only exists for backwards compatability. All code was moved to 
 * Doctrine_Core and this class extends Doctrine_Core
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @author      Lukas Smith <smith@pooteeweet.org> (PEAR MDB2 library)
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 7490 $
 */
class Doctrine extends Doctrine_Core
{
  public function __construct(){
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require_once APPPATH.'config/database.php';

    // Allow Doctrine to load Model classes automatically:
    spl_autoload_register( array('Doctrine', 'autoload') );
    
    // Load our database connections into Doctrine_Manager:
    $dsn = 'mysql' .
      '://' . 'userpfc' .
      ':'   . 'pfcpass' .
      '@'   . 'localhost'.
      '/'   . 'pfc_development';

    Doctrine_Manager::connection($dsn, 'default');
    

    // Load the Model class and tell Doctrine where our models are located:
    require_once BASEPATH.'core/Model.php';
    Doctrine::loadModels(APPPATH.'models');

    // Allow the use of mutators:
    Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);

    // Set all table columns to NOT NULL and UNSIGNED (for INTs) by default:
    Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_DEFAULT_COLUMN_OPTIONS, array('notnull' => true, 'unsigned' => true));

    // Set the default primary key to be named 'id' (4-byte INT):
    Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_DEFAULT_IDENTIFIER_OPTIONS, array('name' => 'id', 'type' => 'integer', 'length' => 4));

  }
}