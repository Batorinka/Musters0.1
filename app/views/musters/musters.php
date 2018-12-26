<?php $this->layout('layout', ['title' => 'Поверки', 'col_md_n' => '12']) ?>

<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col" class="col-md-2">Название предприятия</th>
      <th scope="col">Название прибора</th>
      <th scope="col">Заводской номер</th>
      <th scope="col">Дата последней поверки</th>
      <th scope="col">Межповерочный интервал</th>
      <th scope="col">Дата следующей поверки</th>
    </tr>
  </thead>
  <tbody>
	<?php foreach ($objects as $object): ?>
	  <tr>
	  <td rowspan="<?= $object['quantity_of_musters']; ?>">
		  <a href="/object/<?= $object['id'] ?>">
		    <?= $object['name']; ?>
		  </a>
	  </td>
	
	  <?php foreach($musters as $muster): ?>
		  <?php if ($muster['object_id'] == $object['id']): ?>
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
		  <?php endif; ?>
	  <?php endforeach; ?>
	
	<?php endforeach; ?>
  </tbody>
</table>