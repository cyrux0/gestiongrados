  <?php echo form_open('asignaturas/create','', $hidden); ?>
    
Código: <?php echo form_input('codigo', ''); ?><br />
Nombre: <?php echo form_input('nombre',''); ?><br />
    Créditos: <?php echo form_input('creditos', ''); ?><br />
    Materia: <?php echo form_input('materia',''); ?><br />
    Departamento: <?php echo form_input('departamento',''); ?><br />
    Horas Presenciales: <?php echo form_input('horas_presen',''); ?><br />
    Horas No Presenciales: <?php echo form_input('horas_no_presen',''); ?><br />
    <?php echo form_submit('add_asig_submit', 'Enviar'); ?> | <?= anchor('titulaciones/index', 'Cancelar') ?>
    
    <?php echo form_close(); ?>
