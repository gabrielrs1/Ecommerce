<?php 

session_start();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
// use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
// $app = new \Slim\Slim();

// $app->config('debug', true);

$app->get('/', function(Request $request, Response $response, $args) {
	$page = new Page();

	$response->getBody($page->setTpl('index'));

	return $response;
});

$app->get('/admin', function(Request $request, Response $response, $args) {
	User::verifyLogin();

	$page = new PageAdmin();

	$response->getBody($page->setTpl('index'));

	return $response;
});

$app->get('/admin/login', function(Request $request, Response $response, $args) {
	$page = new PageAdmin([
		'header' => false,
		'footer' => false
	]);
	
	$response->getBody($page->setTpl('login'));

	return $response;
});

$app->post('/admin/login', function(Request $request, Response $response, $args) {
	User::login($_POST["login"], $_POST["password"]);

	header('Location: /admin');
	
	exit;
});

$app->get('/admin/logout', function() {
	User::logout();

	header("Location: /admin/login");

	exit;
});

$app->run();

 ?>