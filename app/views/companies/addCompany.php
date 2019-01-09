<?php $this->layout('layout', ['title' => 'Добавить объект', 'col_md_n' => '6']) ?>

<form action="/addobject" method="post">
  <div class="form-group">
    <label for="name">Название предприятия</label>
    <input type="text" name="name" class="form-control" placeholder="Введите название предприятия">
  </div>
  <div class="form-group">
    <label for="email">Адрес электронной почты для рассылки</label>
    <input type="text" name="email" class="form-control" placeholder="Введите email">
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
