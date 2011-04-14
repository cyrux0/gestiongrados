<?php
require_once('application/helpers/doctrine_helper.php');
Doctrine_Core::generateModelsFromDb('application/test_models/', array('default'), array('generateTableClasses' => True));


