<?php $this->layout('layout', ['title' => "{$device['name']} - {$type['name']}", 'col_md_n' => '12']) ?>

<h4>Поверки</h4>

<table class="table table-bordered">
	<thead>
	<tr>
		<th scope="col" class="col-md-3">Название предприятия</th>
		<th scope="col">Заводской номер</th>
		<th scope="col">Дата последней поверки</th>
		<th scope="col">Межповерочный интервал</th>
		<th scope="col">Дата следующей поверки</th>
		<th scope="col"></th>
	</tr>
	</thead>
	<tbody>
		<tr>
		
		<?php foreach($musters as $muster): ?>
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
			<td class="dropdown">
				<a href="#" class="dropdown-toggle btn btn-info" data-toggle="dropdown">
					<span class="glyphicon glyphicon-cog"></span>
				</a>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="/updatemusterform/<?=$muster['id'];?>">Редактировать</a></li>
					<li><a href="/deletemuster/<?=$muster['id'];?>"
					onclick="return confirm('Вы уверенны?')">Удалить</a></li>
				</ul>
			</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>