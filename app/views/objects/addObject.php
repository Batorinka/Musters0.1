<?php $this->layout('layout', ['title' => 'Добавить объект', 'col_md_n' => '6']) ?>

<form action="/addobject" method="post">
	<div class="form-group">
		<label for="object_name">Название предприятия</label><br>
		<select class="js-example-basic-single" name="company_id">
			<?php foreach ($companies as $company): ?>
				<option value="<?= $company['id']?>"><?= $company['name_sub']?></option>
			<?php endforeach; ?>
		</select>
		<a class="btn btn-primary"  href="/addcompanyform" target="_blank">
			<span class="glyphicon glyphicon-plus"></span>
		</a>
	</div>
	<div class="form-group">
		<label for="name">Название объекта</label>
		<input type="text" name="name" class="form-control" placeholder="Введите название объекта">
	</div>
	<div class="form-group">
		<label for="email">Адрес объекта</label>
		<input type="text" name="address" class="form-control" placeholder="Введите адрес объекта">
	</div>
	<button type="submit" class="btn btn-primary">Сохранить</button>
</form>
