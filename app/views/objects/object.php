<?php $this->layout('layout', ['title' =>"{$object['company_name']} - {$object['name']}", 'col_md_n' => '12']) ?>

<h4>Поверки</h4>

<table class="table table-bordered">
	<thead>
	<tr>
		<th scope="col">Название прибора</th>
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
				<a href="/device/<?= $muster['device_id'] ?>">
					<?= $muster['device_name'] ?>
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
					<li><a href="/updatemusterform/<?=$muster['id'];?>" target="_blank">Редактировать</a></li>
					<li><a href="/deletemuster/<?=$muster['id'];?>"
					       onclick="return confirm('Вы уверенны?')">Удалить</a></li>
				</ul>
			</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h4>Общая информация</h4>

<table class="table">

	<tbody>
	<tr>
		<td>
			Адрес электронной почты для рассылки
		</td>
		<td>
			<?= $object['email'] ?>
		</td>
	</tr>
	</tbody>
</table>