<?php echo doctype(); ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
  <title>ADD TITULACIONES</title>
</head>
<body>
  <?php $this->load->view('titulaciones/_form', $data); ?></body>
</html>
