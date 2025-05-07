<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

// Ruta raíz
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Bienvenido a la capa de negocio");
    return $response;
});

// Agrupación de rutas
$app->group('/api', function (RouteCollectorProxy $api) {
    $api->group('/producto', function(RouteCollectorProxy $producto){
        $producto->get('/read[/{id}]', ProductoController::class . ':read');
        $producto->post('/create', ProductoController::class . ':create');
    });
});
