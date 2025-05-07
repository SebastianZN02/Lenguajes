<?php
namespace App;

use App\controllers\Artefacto;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

// Agrupar las rutas relacionadas con 'artefacto'
$app->group('/api', function (RouteCollectorProxy $api) {
    $api->group('/artefacto', function(RouteCollectorProxy $artefacto) {
        // Usar el controlador ArtefactoController para las rutas
        $artefacto->get('/read[/{id}]', Artefacto::class . ':read');  // Leer artefacto(s)
        $artefacto->post('/create', Artefacto::class . ':create');   // Crear artefacto
    });
});

$app->get('/artefacto', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Accediendo a artefacto!");
    return $response;
});

$app->get('/artefacto/read', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Accediendo a artefacto!");
    return $response;
});
