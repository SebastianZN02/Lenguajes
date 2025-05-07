<?php
    namespace App\Controllers;
    
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Routing\RouteCollectorProxy;

    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });
    $app->group('/api', function (RouteCollectorProxy $api) {
        $api->group('/producto', function(RouteCollectorProxy $producto){
            $producto->get('/read[/{id}]', Producto::class . ':read'); //*Lectura de un producto
            $producto->post('/create', Producto::class . ':create');
            $producto->put('/update[/{id}]', Producto::class . ':update');
        });
    });
