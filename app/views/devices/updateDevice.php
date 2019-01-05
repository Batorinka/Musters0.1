<?php $this->layout('layout', ['title' => 'Редактировать прибор', 'col_md_n' => '6']) ?>

<form action="/updatedevice/<?=$device['id'];?>" method="post">
	<div class="form-group">
		<label for="name">Название прибора</label>
		<input type="text" name="name" class="form-control" placeholder="Введите название прибора" value="<?=$device['name']?>">
	</div>
	<div class="form-group">
		<label for="object_name">Тип прибора</label><br>
		<select class="js-example-basic-single" name="type_id">
			<?php foreach ($types as $type): ?>
				<option value="<?= $type['id']?>"
					<?= ($type['id'] == $device['type_id']) ? 'selected':''?>
				><?= $type['name']?></option>
			<?php endforeach; ?>
		</select>
	</div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
