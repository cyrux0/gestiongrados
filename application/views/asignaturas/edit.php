<?php echo doctype(); ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Edit ASIGNATURAS</title>	
</head>
<body>
  <h1>Editando asignatura <?php echo $nombre_asignatura; ?></h1>
  <?php $this->load->view('asignaturas/_form', $data); ?> 
</body>
</html>
