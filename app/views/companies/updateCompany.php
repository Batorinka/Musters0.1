<?php $this->layout('layout', ['title' => 'Редактировать объект', 'col_md_n' => '6']) ?>

<form action="/updateobject/<?=$object['id'];?>" method="post">
  <div class="form-group">
    <label for="name">Название предприятия</label>
    <input type="text" name="name" class="form-control" placeholder="Введите название предприятия" value="<?=$object['name']?>">
  </div>
  <div class="form-group">
	<label for="email">Адрес электронной почты для рассылки</label>
	<input type="text" name="email" class="form-control" placeholder="Введите email" value="<?=$object['email']?>">
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
