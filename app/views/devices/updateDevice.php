<?php $this->layout('layout', ['title' => 'Редактировать прибор', 'col_md_n' => '6']) ?>

<form action="/updatedevice/<?=$device['id'];?>" method="post">
  <div class="form-group">
    <label for="name">Название прибора</label>
    <input type="text" name="name" class="form-control" placeholder="Введите название прибора" value="<?=$device['name']?>">
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
