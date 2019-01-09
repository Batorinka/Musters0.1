<?php $this->layout('layout', ['title' => 'Тест', 'col_md_n' => '12']) ?>

<div class="col-md-6">
	<h1>Ввод</h1>
	<div class="form-group">
		<label>Название статьи</label>
		<input type="text" name="title" class="form-control title" placeholder="Введите название статьи">
	</div>
	<div class="form-group">
		<label>Имя автора</label>
		<input type="text" name="name" class="form-control name" placeholder="Введите имя автора">
	</div>
	<button type="submit" class="btn btn-primary submit">Сохранить</button>
</div>
<div class="col-md-6">
	<h1>Статьи</h1>
	<table class="table">
		<thead>
		<tr>
			<th scope="col">Название статьи</th>
			<th scope="col">Автор</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($tests as $test): ?>
			<tr>
				<td><?= $test['title']; ?></td>
				<td><?= $test['name']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>



<script>
	$(document).ready(function () {
		$('button.submit').on('click', function () {
			var titleValue = $('input.title').val();
			var nameValue = $('input.name').val();

            $.ajax({
                method: "POST",
                url: "/testAjax",
                data: { title: titleValue,name: nameValue }
            })
                .done(function(  ) {
                    //alert( "Data Saved: " + msg );
                });
            $('input.title').val('');
            $('input.name').val('');
            $.ajax({
                url: "/testAjax",
                cache: false
            })
                .done(function( html ) {
                    $( "#results" ).append( html );
                });
        })
    })
</script>