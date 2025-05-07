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
        $api->group('/artefacto', function(RouteCollectorProxy $artefacto){
            $artefacto->get('[/read/{id}]', Artefacto::class . ':read'); //*Lectura de un artefacto
            $artefacto->post('', Artefacto::class . ':create');//*Crear un artefacto
            $artefacto->put('/{id}', Artefacto::class . ':update');//*Actualizar un artefacto
            $artefacto->delete('/{id}', Artefacto::class . ':delete');//*Eliminar un artefacto
            $artefacto->get('/filtrar', Artefacto::class . ':filtrar');//*Filtrar artefacto
        });
    });
