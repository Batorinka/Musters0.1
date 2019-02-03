<?php $this->layout('layout', ['title' => 'Редактировать объект', 'col_md_n' => '6']) ?>

<form action="/updateobject/<?=$object['id'];?>" method="post">
	<div class="form-group">
		<label for="object_name">Название предприятия</label><br>
		<select class="js-example-basic-single" name="company_id">
			<?php foreach ($companies as $company): ?>
				<option value="<?= $company['id']?>"
					<?= ($company['id'] == $object['company_id']) ? 'selected':''?>
				><?= $company['name_sub']?></option>
			<?php endforeach; ?>
		</select>
		<a class="btn btn-primary"  href="/addcompanyform" target="_blank">
			<span class="glyphicon glyphicon-plus"></span>
		</a>
	</div>
	<div class="form-group">
		<label for="name">Название объекта</label>
		<input type="text" name="name" class="form-control" placeholder="Введите название объекта" value="<?=$object['name']?>">
	</div>
	<div class="form-group">
		<label for="email">Адрес объекта</label>
		<input type="text" name="email" class="form-control" placeholder="Введите адрес объекта" value="<?=$object['address']?>">
	</div>
	<button type="submit" class="btn btn-primary">Сохранить</button>
</form>
