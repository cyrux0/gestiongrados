<p>
    No hay ningún Plan Docente para esta asignatura y curso
</p>
<? if(Current_User::logged_in(2)): ?>
<p>
    <?= anchor( "asignaturas/add_carga/$id_asignatura/$id_curso", "Crear una") ?>
</p>
<? endif;?>