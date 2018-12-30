<?php

if( !session_id() ) @session_start();

require '../vendor/autoload.php';
use DI\ContainerBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;
use Aura\SqlQuery\QueryFactory;
use Carbon\Carbon;
use App\AppConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpWord\PhpWord;



$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions([
  PHPMailer::class => function() {
	return new PHPMailer(true);
  },
  PDO::class => function() {
    $appConfig = new AppConfig;
    $config = $appConfig->db();

    return new PDO("{$config['driver']}:host={$config['host']};
                            dbname={$config['database_name']};
                            charset={$config['charset']};",
	                        $config['username'], $config['password']);
  },
  Engine::class => function() {
    return new Engine("../app/views");
  },
  Auth::class => function($container) {
    return new Auth($container->get('PDO'));
  },
  QueryFactory::class => function() {
    return new QueryFactory('mysql');
  }
]);
$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute('GET', '/', ['App\controllers\MustersController', 'getMusters']);
	if (isset($_SESSION['auth_logged_in'])){
		$r->addRoute('GET', '/addmusterform', ['App\controllers\MustersController', 'addMusterForm']);
		$r->addRoute('POST', '/addmuster', ['App\controllers\MustersController', 'addMuster']);
		$r->addRoute('GET', '/updatemusterform/{id:\d+}', ['App\controllers\MustersController', 'updateMusterForm']);
		$r->addRoute('POST', '/updatemuster/{id:\d+}', ['App\controllers\MustersController', 'updateMuster']);
		$r->addRoute('GET', '/deletemuster/{id:\d+}', ['App\controllers\MustersController', 'deleteMuster']);
		
		$r->addRoute('GET', '/catalogues/objects', ['App\controllers\ObjectsController', 'getObjects']);
		$r->addRoute('GET', '/object/{id:\d+}', ['App\controllers\ObjectsController', 'getObject']);
		$r->addRoute('GET', '/addobjectform', ['App\controllers\ObjectsController', 'addObjectForm']);
		$r->addRoute('POST', '/addobject', ['App\controllers\ObjectsController', 'addObject']);
		$r->addRoute('GET', '/updateobjectform/{id:\d+}', ['App\controllers\ObjectsController', 'updateObjectForm']);
		$r->addRoute('POST', '/updateobject/{id:\d+}', ['App\controllers\ObjectsController', 'updateObject']);
		$r->addRoute('GET', '/deleteobject/{id:\d+}', ['App\controllers\ObjectsController', 'deleteObject']);
		
		$r->addRoute('GET', '/catalogues/devices', ['App\controllers\DevicesController', 'getDevices']);
		$r->addRoute('GET', '/device/{id:\d+}', ['App\controllers\DevicesController', 'getDevice']);
		$r->addRoute('GET', '/adddeviceform', ['App\controllers\DevicesController', 'addDeviceForm']);
		$r->addRoute('POST', '/adddevice', ['App\controllers\DevicesController', 'addDevice']);
		$r->addRoute('GET', '/updatedeviceform/{id:\d+}', ['App\controllers\DevicesController', 'updateDeviceForm']);
		$r->addRoute('POST', '/updatedevice/{id:\d+}', ['App\controllers\DevicesController', 'updateDevice']);
		$r->addRoute('GET', '/deletedevice/{id:\d+}', ['App\controllers\DevicesController', 'deleteDevice']);
		
		$r->addRoute('GET', '/overlooked', ['App\controllers\PagesController', 'getOverlooked']);
		$r->addRoute('GET', '/404', ['App\controllers\PagesController', 'pageNotFound']);
		$r->addRoute('GET', '/test', ['App\controllers\PagesController', 'test']);
		$r->addRoute('POST', '/testAjax', ['App\controllers\PagesController', 'testAjax']);
		$r->addRoute('GET', '/send', ['App\controllers\MailController', 'sendMail']);
		$r->addRoute('GET', '/logout', ['App\controllers\AuthController', 'logout']);
	} else {
		$r->addRoute('GET', '/auth', ['App\controllers\AuthController', 'loginForm']);
		$r->addRoute('POST', '/login', ['App\controllers\AuthController', 'login']);
	}
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
	    if (isset($_SESSION['auth_logged_in'])) {
		    header('Location: /404');
	    } else {
		    header('Location: /auth');
	    }
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "метод не разрешен";

        break;
    case FastRoute\Dispatcher::FOUND:
        $container->call($routeInfo[1], [$routeInfo[2]]);
        // ... call $handler with $vars
        break;
}
