<?php


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container;


AppFactory::setContainer($container);
$settings = require __DIR__.'/../app/settings.php';  // returnt eine callback-function die das CI als Parameter hat
$settings($container);

$app = AppFactory::create();



$container->set('db', function()
use ($app)
{
    $setting = $app->getContainer()->get('settings');
    $pdo = new PDO('mysql:host=' . $setting['dbHost']. ';dbname=' . $setting['dbName'], $setting['dbUser'] , $setting['dbPass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
});

$container->set('templating', function(){
    return new Mustache_Engine([
        'loader' => new Mustache_Loader_FilesystemLoader(
            __DIR__ . '/../templates',
            ['extension' => ''] )
    ]);
});

$container->set('session', function(){
    return new \SlimSession\Helper();
});



$app->add(new \Slim\Middleware\Session);

$middleware = require __DIR__ . '/../app/Middleware/middleware.php';
$middleware($app);

/* Routes */

$app->get('/logout', '\App\Controller\AuthController:logout');

$app->group('/secure', function($app){
    $app->get('', '\App\Controller\SecureController:start');
    $app->get('/status', '\App\Controller\SecureController:status');
    $app->get('/usersFood', '\App\Controller\InputController:usersFood');
    $app->get('/ext_source', '\App\Controller\ApiController:external');
    $app->get('/submit', '\App\Controller\InputController:kategorien');
    $app->post('/submit', '\App\Controller\InputController:submit');
    $app->get('/selectdata/{kategorie}', '\App\Controller\InputController:items');
    $app->get('/details/{id:[0-9]+}', '\App\Controller\InputController:details');
})->add(new \App\Middleware\Authenticate($app->getContainer()->get('session')));


$app->any('/', '\App\Controller\AuthController:login');
$app->run();