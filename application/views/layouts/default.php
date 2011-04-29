<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php if (isset($page_title)) echo $page_title; else echo 'El título de la web'; ?></title>
  </head>
  <body>
    {yield}
  </body>
</html>
