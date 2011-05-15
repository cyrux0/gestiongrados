<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/1999/xhtml" >

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>EDIT CARGA GLOBAL</title>	
</head>
<body>
  <h1>Editando carga global de <?php echo $data['result']->cargaglobal->Asignatura->nombre; ?></h1>
  <?php $this->load->view('cargassemanales/_form', $data); ?>
</body>
</html>
