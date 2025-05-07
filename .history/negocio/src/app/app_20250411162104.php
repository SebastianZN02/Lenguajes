<?php

use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../../vendor/autoload.php';

// Crear contenedor de dependencias
$container = new Container();
AppFactory::setContainer($container);

// Crear instancia de la app
$app = AppFactory::create();

// Incluir configuración, conexión y rutas
require __DIR__ . '/config.php';
require __DIR__ . '/conexion.php';
require __DIR__ . '/routes.php';

// Ejecutar la app
$app->run();
