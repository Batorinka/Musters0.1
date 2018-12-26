<?php $this->layout('layout', ['title' => 'Список приборов', 'col_md_n' => '5']) ?>
<a class="btn btn-primary"  href="/adddeviceform">Добавить прибор</a>
<table id="dt" class="table">
  <thead>
    <tr>
	  <th scope="col">Название прибора</th>
	  <th scope="col">Количество поверок</th>
      <th scope="col" class="col-md-3">Действия</th>
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
		  <td><?= $device['quantity_of_musters']; ?></td>
		  <td>
			  <a href="/deletedevice/<?=$device['id'];?>" class="btn btn-danger"
			     onclick="return confirm('Вы уверенны?')"><span class="glyphicon glyphicon-remove"></span></a>
			  <a href="/updatedeviceform/<?=$device['id'];?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
		  </td>
	  </tr>
	<?php endforeach; ?>
  </tbody>
</table>