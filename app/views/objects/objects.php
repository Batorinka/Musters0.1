<?php $this->layout('layout', ['title' => 'Список объектов', 'col_md_n' => '8']) ?>
<a class="btn btn-primary"  href="/addobjectform">Добавить объект</a>
<table id="dt" class="table">
  <thead>
    <tr>
      <th scope="col">Название предприятия</th>
      <th scope="col">Название объекта</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
	<?php foreach ($objects as $object): ?>
	  <tr>
		  <td>
			  <?foreach ($companies as $company) :?>
			  <a href="/company/<?= $company['id'] ?>">
					  <?= ($company['id'] == $object['company_id']) ? $company['name_sub'] : ''?>
			  </a>
			  <?endforeach;?>
		  </td>
		  <td>
			  <a href="/object/<?= $object['id'] ?>">
			    <?= $object['name']; ?>
			  </a>
		  </td>
		  <td class="dropdown">
			  <a href="#" class="dropdown-toggle btn btn-info" data-toggle="dropdown">
				  <span class="glyphicon glyphicon-cog"></span>
			  </a>
			  <ul class="dropdown-menu dropdown-menu-right">
				  <li><a href="/updateobjectform/<?=$object['id'];?>" target="_blank">Редактировать</a></li>
				  <li><a href="/deleteobject/<?=$object['id'];?>"
				         onclick="return confirm('Вы уверенны?')">Удалить</a></li>
			  </ul>
		  </td>
	  </tr>
	<?php endforeach; ?>
  </tbody>
</table>