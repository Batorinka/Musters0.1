<?php $this->layout('layout', ['title' => 'Добавить прибор', 'col_md_n' => '6']) ?>

<form action="/addmuster" method="post">
  <div class="form-group">
    <label for="object_name">Название объекта</label><br>
    <select class="js-example-basic-single" name="object_id">
	  <?php foreach ($objects as $object): ?>
	    <?php foreach ($companies as $company): ?>
		  <?if ($company['id'] == $object['company_id']) :?>
		     <option value="<?= $object['id']?>">
			     <?="{$company['name_sub']} ({$object['name']})"?>
		     </option>
		  <?endif;?>
	    <?php endforeach; ?>
	  <?php endforeach; ?>
    </select>
    <a class="btn btn-primary" href="/addobjectform" target="_blank">
	  <span class="glyphicon glyphicon-plus"></span>
    </a>
  </div>
  <div class="form-group">
    <label for="name">Название прибора</label><br>
	<select class="js-example-basic-single" name="device_id">
	  <?php foreach ($devices as $device): ?>
		  <option value="<?= $device['id']?>"><?= $device['name']?></option>
	  <?php endforeach; ?>
	</select>
	<a class="btn btn-primary" href="/adddeviceform" target="_blank">
	  <span class="glyphicon glyphicon-plus"></span>
	</a>
  </div>
  <div class="form-group">
    <label for="number">Заводской номер</label>
    <input type="text" name="number" class="form-control" placeholder="Введите заводской номер">
  </div>
  <div class="form-group">
    <label for="last_date">Дата последней поверки</label>
    <input type="date" name="last_date" class="form-control" placeholder="Введите дату последней поверки">
  </div>
  <div class="form-group">
    <label for="interval_of_muster">Межповерочный интервал</label>
    <input type="number" name="interval_of_muster" class="form-control" placeholder="Введите межповерочный интервал">
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
