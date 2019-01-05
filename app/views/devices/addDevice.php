<?php $this->layout('layout', ['title' => 'Добавить прибор', 'col_md_n' => '6']) ?>

<form action="/adddevice" method="post">
	<div class="form-group">
		<label for="name">Наименование прибора</label>
		<input type="text" name="name" class="form-control" placeholder="Введите название прибора">
	</div>
	<div class="form-group">
		<label for="object_name">Тип прибора</label><br>
		<select class="js-example-basic-single" name="type_id">
			<?php foreach ($types as $type): ?>
				<option value="<?= $type['id']?>"><?= $type['name']?></option>
			<?php endforeach; ?>
		</select>
	</div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
