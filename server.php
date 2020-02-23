<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use React\EventLoop\Factory;
use React\Http\Server;
use Sapphire\App\Core\ErrorHandler;
use Sapphire\App\Core\JsonRequestDecoder;
use Sapphire\App\Core\Router;
use Sapphire\App\Orders\Controller\CreateOrder\Controller;
use Sapphire\App\Orders\Controller\DeleteOrder;
use Sapphire\App\Orders\Controller\GetAllOrders;
use Sapphire\App\Orders\Controller\GetOrderById;
use Sapphire\App\Orders\Storage as Orders;
use Sapphire\App\Products\Controller\CreateProduct;
use Sapphire\App\Products\Controller\DeleteProduct;
use Sapphire\App\Products\Controller\GetAllProducts;
use Sapphire\App\Products\Controller\GetProductById;
use Sapphire\App\Products\Controller\UpdateProduct;
use Sapphire\App\Products\Storage as Products;

require __DIR__ . '/vendor/autoload.php';

$requiredEnvFields = [
    'JWT_KEY',
    'DB_HOST',
    'DB_USER',
    'DB_PWD',
    'DB_NAME',

];
$env = Dotenv::createImmutable(__DIR__);
$env->load();
$env->required($requiredEnvFields)->notEmpty();

$loop = Factory::create();

$factory = new \React\MySQL\Factory($loop);
$uri = getenv('DB_USER') . ':' . getenv('DB_PWD') . '@' . getenv('DB_HOST') . '/' . getenv('DB_NAME');
$connection = $factory->createLazyConnection($uri);

$products = new Products($connection);
$orders = new Orders($connection);
$dispatcher = require __DIR__ . '/src/routes.php';

$middlewares = [
    new ErrorHandler,
    new JsonRequestDecoder,
    new Router($dispatcher),
];

$server = new Server($middlewares);

$socket = new \React\Socket\Server(getenv('SERVER_URI'), $loop);
$server->listen($socket);

$server->on('error', static function (Throwable $error) {
    echo 'Error: ' . $error->getMessage() . PHP_EOL;
});

printf('Listening on %s', str_replace('tcp', 'http', $socket->getAddress()) . PHP_EOL);

$loop->run();
