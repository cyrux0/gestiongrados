
<ul>
<? foreach($eventos as $evento): ?>
<li><?= anchor('#', $evento['fecha_inicial']) ?> | <?= anchor('eventos/delete/' . $evento['id'], 'X')?></li>
<? endforeach ?>

</ul>

<div id="calendar_eventos_index">
<?= site_url('eventos/fetch_events/' . $this->uri->segment(3)) ?>
</div>

<div id="form_add_event">
    <?= form_open('eventos/create/') ?>
        <input type="hidden" name="curso_id" value="<?= $id_curso ?>" />
        <p>
					<span>Fecha de inicio: </span><br/>
					<span id="start_date_holder"></span>
					<input type="hidden" name="fecha_inicial" value="" ?>
					<br/>
		</p><p>
					<span>Fecha de finalización: </span><br/>
					<span id="end_date_holder"></span>
					<input type="hidden" name="fecha_final" value="" ?>
		</p> 
            <p>
        		<label for="title">Título: </label><br/>
		      	<input type="text" class="text ui-widget-content ui-corner-all" name="nombre_evento" />
			</p>
            <span id="delete_url" style="display:none;"><?= site_url('eventos/delete/') ?></span>
    </form>
    
</div>
<? if(Current_User::logged_in(2)): ?>
<?= anchor('eventos/add/' . $id_curso, 'Añadir un nuevo evento') ?>
<? endif; ?>

