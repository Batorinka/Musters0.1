<?php $this->layout('layout', ['title' => 'Просроченные поверки', 'col_md_n' => '12']) ?>

<table id="dt" class="table table-bordered">
  <thead>
    <tr>
      <th scope="col" class="col-md-3">Название предприятия</th>
      <th scope="col">Название прибора</th>
      <th scope="col">Заводской номер</th>
      <th scope="col" class="col-md-1">Дата последней поверки</th>
      <th scope="col" class="col-md-1">Межповерочный интервал</th>
      <th scope="col" class="col-md-1">Дата следующей поверки</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($musters as $muster): ?>
	  <tr>
		  <td>
			  <a href="/object/<?= $muster['object_id'] ?>">
				  <?= $objects[
				  array_search(
					  $muster['object_id'],
					  array_column($objects, 'id')
				  )
				  ]['name']; ?>
			  </a>
		  </td>
	
		  <td>
			  <a href="/device/<?= $muster['device_id'] ?>">
				  <?= $devices[
				  array_search(
					  $muster['device_id'],
					  array_column($devices, 'id')
				  )
				  ]['name']; ?>
			  </a>
		  </td>
		  <td>
			  <?= $muster['number'] ?>
		  </td>
		  <td>
			  <?= $muster['last_date'] ?>
		  </td>
		  <td>
			  <?= $muster['interval_of_muster'] ?>
		  </td>
		  <td class="<?= $muster['is_overlooked'] ?>
<?= $muster['is_overlooked_in_month'] ?>">
			  <?= $muster['next_date'] ?>
		  </td>
	  </tr>
  <?php endforeach; ?>
  
  </tbody>
</table>