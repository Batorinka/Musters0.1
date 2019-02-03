<?php $this->layout('layout', ['title' => 'Список предприятий', 'col_md_n' => '5']) ?>
<a class="btn btn-primary"  href="/addcompanyform">Добавить предприятие</a>
<table id="dt" class="table">
  <thead>
    <tr>
      <th scope="col">Название предприятия</th>
      <th scope="col">Количество объектов</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
	<?php foreach ($companies as $company): ?>
	  <tr>
		  <td>
			  <a href="/company/<?= $company['id'] ?>">
			    <?= $company['name_sub']; ?>
			  </a>
		  </td>
		  <td>
			  <?= $company['quantity_of_objects']; ?>
		  </td>
		  <td class="dropdown">
			  <a href="#" class="dropdown-toggle btn btn-info" data-toggle="dropdown">
				  <span class="glyphicon glyphicon-cog"></span>
			  </a>
			  <ul class="dropdown-menu dropdown-menu-right">
				  <li><a href="/updatecompanyform/<?=$company['id'];?>">Редактировать</a></li>
				  <li><a href="/deletecompany/<?=$company['id'];?>"
				         onclick="return confirm('Вы уверенны?')">Удалить</a></li>
			  </ul>
		  </td>
	  </tr>
	<?php endforeach; ?>
  </tbody>
</table>