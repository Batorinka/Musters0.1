<?php $this->layout('layout', ['title' => 'Список приборов', 'col_md_n' => '6']) ?>
<a class="btn btn-primary"  href="/adddeviceform">Добавить прибор</a>
<table id="dt" class="table">
  <thead>
    <tr>
	  <th scope="col">Название прибора</th>
	  <th scope="col">Тип прибора</th>
	  <th scope="col">Количество поверок</th>
      <th scope="col" class="col-md-1"></th>
    </tr>
  </thead>
  <tbody>
	<?php foreach ($devices as $device): ?>
	  <tr>
		  <td>
			  <a href="/device/<?= $device['id'] ?>">
			    <?= $device['name']; ?>
			  </a>
		  </td>
		  <td><?= $device['type']; ?></td>
		  <td><?= $device['quantity_of_musters']; ?></td>
		  <td class="dropdown">
			  <a href="#" class="dropdown-toggle btn btn-info" data-toggle="dropdown">
				  <span class="glyphicon glyphicon-cog"></span>
			  </a>
			  <ul class="dropdown-menu dropdown-menu-right">
				  <li><a href="/updatedeviceform/<?=$device['id'];?>" target="_blank">Редактировать</a></li>
				  <li><a href="/deletedevice/<?=$device['id'];?>"
				         onclick="return confirm('Вы уверенны?')">Удалить</a></li>
			  </ul>
		  </td>
	  </tr>
	<?php endforeach; ?>
  </tbody>
</table>