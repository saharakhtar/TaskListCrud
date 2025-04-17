<?php


use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use App\Middleware\ApiKeyMiddleware;
use Illuminate\Database\Capsule\Manager as Capsule;
require __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'pgsql',
    'host'      => $_ENV['DB_HOST'] ?? 'db',
    'database'  => $_ENV['DB_NAME'] ?? 'tasks_db',
    'username'  => $_ENV['DB_USER'] ?? 'postgres',
    'password'  => $_ENV['DB_PASS'] ?? 'root',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->add(new ApiKeyMiddleware());



(require __DIR__ . '/../app/routes/tasks.php')($app);

$app->run();