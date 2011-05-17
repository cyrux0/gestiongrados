<?php echo doctype(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
    <title><?php if (isset($page_title)) echo $page_title; else echo 'El título de la web'; ?></title>
  </head>
  <body>
    {yield}
  </body>
</html>
