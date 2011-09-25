
--------------------
<? $data = array(8 => 'http://www.google.es', 15 => 'http://localhost/gestgrados'); ?>
<?= $this->calendar->generate($this->uri->segment(3), $this->uri->segment(4), $data) ?>
--------------------