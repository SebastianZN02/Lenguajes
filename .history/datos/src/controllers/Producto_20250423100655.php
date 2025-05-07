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
            $campos .= $key . ", ";
        };
        $campos = substr($campos, 0, -2); //Eliminar la última coma y espacio


        
        $valores = "";
        foreach ($body as $key => $value) {
            $valores .= ":" . $value . ", ";
        };
        $valores = substr($valores, 0, -2) . ")"; //Eliminar la última coma y espacio
        die($valores);
        die($campos);
        
        $sql = "INSERT INTO productos( $campos  VALUES ($valores);";
        die($sql);

       // $con = $this->container->get('base_datos');
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