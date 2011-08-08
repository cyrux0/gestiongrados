<ul>
<? foreach($cursos as $curso): ?>

<li><?= anchor('#', date_format(date_create($curso->inicio_semestre1), "Y") . '/' . date_format(date_create($curso->fin_semestre2), "Y")) ?> | <?= anchor('cursos/delete/' . $curso->id, 'X') ?></li>
<? endforeach ?>

</ul>


