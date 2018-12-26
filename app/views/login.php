<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="/public/resources/css/auth.css">
	<title>Вход</title>
</head>
<body>
<?= flash()->display(); ?>
<div class="login-page">
	<div class="form">
		<form class="login-form"  action="/login" method="post">
			<input type="text" name="email" placeholder="логин"/>
			<input type="password" name="password" placeholder="пароль"/>
			<button>вход</button>
			<p class="message"><a href="#">Забыл пароль?</a></p>
		</form>
	</div>
</div>

</body>
</html>