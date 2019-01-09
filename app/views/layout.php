<html>
<head>
    <title><?=$this->e($title)?></title>
	<link rel="shortcut icon" href="/public/resources/images/flammable.png" type="image/x-icon">
<!--	<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="/public/resources/css/datatable.css">
	<link rel="stylesheet" href="/public/resources/css/style.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<script src="/public/resources/js/script.js"></script>
	
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>
<body class="bg-dark">
<nav class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Поверки<span class="caret"></span></a>
				  <ul class="dropdown-menu">
					  <li><a href="/">Все поверки</a></li>
					  <li><a href="/overlooked">Просроченные поверки</a></li>
				  </ul>
				</li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Справочники<span class="caret"></span></a>
				  <ul class="dropdown-menu">
					  <li><a href="/catalogues/companies">Предприятия</a></li>
					  <li><a href="/catalogues/objects">Объекты</a></li>
					  <li><a href="/catalogues/devices">Приборы</a></li>
				  </ul>
				</li>
				<li><a href="/test">Тест</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if (isset($_SESSION['auth_logged_in'])) :?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Действия<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="/send">Сделать рассылку</a></li>
						<li><a href="/logout">Выйти</a></li>
					</ul>
				</li>
				<?php endif;?>
				<li><a class="btn btn-primary" href="/addmusterform" target="_blank">
						<span class="btn-plus glyphicon glyphicon-plus"></span>
					</a></li>
			</ul>
		</div>
	</div>
</nav>

<div class="container">
	<div class="row">
		<?= flash()->display(); ?>
		<div class="col-md-<?=$this->e($col_md_n)?>">
			<h3><?=$this->e($title)?></h3>
			<?=$this->section('content')?>
        </div>
  </div>
</div>


</body>
</html>
