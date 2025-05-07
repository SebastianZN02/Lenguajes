<?php
namespace App\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;

use PDO;

class Producto
{
    protected $container;

    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }

    public function read(Request $request, Response $response, $args)
    {
        $sql = "SELECT * FROM productos";

        if (isset($args["id"])) {
            $sql .= " WHERE id = :id";
        }
        $sql .= " LIMIT 0, 5";
        $con = $this->container->get('base_datos');
        $query = $con->prepare($sql);
        if(isset($args['id'])){
            $query->execute(["id" => $args['id']]);
        } else {
            $query->execute();
        }

        $res = $query->fetchAll();

        $status = $query->rowCount() > 0 ? 200 : 204;
        $query = null;
        $con = null;

        $response->getBody()->write(json_encode($res));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);

    }

    public function create(Request $request, Response $response, $args)
    {
        $body = json_decode($request->getBody());
        $sql = "INSERT INTO productos (";
        $campos = "";

        foreach ($body as $key => $value) {
            $campos .= $key . '',;
        }
        
        id, codigo_producto, precio_compra_producto,
                precio_venta_producto, utilidad, fecha_creacion_producto) 
                VALUES($body->id, $body->codigo_producto, $body->precio_compra_producto,
                $body->precio_venta_producto, $body->utilidad, $body->fecha_creacion_producto);";


        $con = $this->container->get('base_datos');
        $query = $con->prepare($sql);

        $data = [];
        foreach ($body as $key => $value) { 
            $data[$key] = $value;
        }

        $query->execute($data);

        $status = $query->rowCount() > 0 ? 201 : 409; //201 Creado, 409 Conflicto

        $query = null; //Cerrar la consulta
        $con = null; //Cerrar la conexión

        return $response->withStatus($status);
    }
}