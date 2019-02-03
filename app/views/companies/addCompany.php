<?php $this->layout('layout', ['title' => 'Добавить предприятие', 'col_md_n' => '6']) ?>

<form action="/addcompany" method="post">
  <div class="form-group">
    <label for="name_sub">Сокращенное название предприятия</label>
    <input type="text" name="name_sub" id="sub" class="form-control" placeholder="Введите сокращенное название предприятия">
  </div>
  <div class="form-group">
    <label for="name_full">Полное название предприятия</label>
    <a href="#" class="btn btn-primary"
       onclick="document.getElementById('full').value = document.getElementById('sub').value"
    >Копировать сокращенное в полное</a>
    <input type="text" name="name_full" id="full" class="form-control" placeholder="Введите полное название предприятия">
  </div>
  <div class="form-group">
    <label for="contract_type">Тип договора</label>
    <select class="form-control" name="contract_type">
          <?php foreach ($contract_types as $contract_type): ?>
            <option value="<?= $contract_type['id']?>"><?= $contract_type['name']?></option>
          <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label for="contract_number">Номер договора</label>
    <input type="text" name="contract_number" class="form-control" placeholder="Введите номер договора">
  </div>
  <div class="form-group">
    <label for="contract_date">Дата подписания договора</label>
    <input type="date" name="contract_date" class="form-control" placeholder="Введите дату последней поверки">
  </div>
  <div class="form-group">
    <label for="email">Адрес электронной почты</label>
    <input type="text" name="email" class="form-control" placeholder="Введите адрес электронной почты">
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
