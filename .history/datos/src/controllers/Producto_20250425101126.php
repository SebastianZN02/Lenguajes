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

    public function read(Request $request, Response $response, $args) // Lectura de un producto
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

    public function create(Request $request, Response $response, $args) //Crear un producto
    {
        $body = json_decode($request->getBody());
        $sql = "INSERT INTO productos (";
        
        
        $campos = "";
        foreach ($body as $key => $value) {
            $campos .= $key . ", ";
        };
        $campos = substr($campos, 0, -2); //Eliminar la última coma y espacio


        $params = ""; 
        foreach ($body as $key => $value) {
            $params .= ":" . $key . ", ";
        };
        $params = substr($params, 0, -2); //Eliminar la última coma y espacio
        
        $sql = "INSERT INTO productos($campos)  VALUES ($params);";

        //die($sql);

        $con = $this->container->get('base_datos');
        $query = $con->prepare($sql);

        foreach($body as $key => $value){
            $TIPO= gettype($value)=="integer" ? PDO::PARAM_INT : PDO::PARAM_STR;
            $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS); // Sanitizar el valor
            $query->bindValue($key, $value, $TIPO);
        };

        $query->execute();

        $status = $query->rowCount() > 0 ? 201 : 409; //201 Creado, 409 Conflicto

        $query = null; //Cerrar la consulta
        $con = null; //Cerrar la conexión

        return $response->withStatus($status);
    }


    public function update(Request $request, Response $response, $args) //*Actualizo un producto
    {
        $body = json_decode($request->getBody());
        
        $id = $args['id'];
        if (isset($body->id)) {
            unset($body->id); //?Eliminar el id del body
        }

        if(isset($body->codigo_producto)) {
            unset($body->codigo_producto); //?Eliminar el codigo_producto del body
        }

        $sql = "UPDATE productos SET ";
        foreach($body as $key => $value) {
            $sql .= "$key = :$key, ";
        }
        $sql = substr($sql, 0, -2); //Eliminar la última coma y espacio
        $sql .= " WHERE id = :id;"; //?Agregar la condición para el id


        $con = $this->container->get('base_datos');
        $query = $con->prepare($sql);


        foreach($body as $key => $value){
            $TIPO= gettype($value)=="integer" ? PDO::PARAM_INT : PDO::PARAM_STR; //?Definir el tipo de dato
            $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS); // Sanitizar el valor
            $query->bindValue($key, $value, $TIPO);
        }

        $query->bindValue("id", $id, PDO::PARAM_INT); //!Agregar el id a la consulta porque lo eliminamos del body

        $query->execute();

        $status = $query->rowCount() > 0 ? 201 : 204; //?201 Creado, 204 Sin contenido

        $query = null; //Cerrar la consulta
        $con = null; //Cerrar la conexión

        return $response->withStatus($status);
    }

    public function delete(Request $request, Response $response, $args) // Lectura de un producto
    {
        $sql = "DELETE FROM productos WHERE id = :id";

        $con = $this->container->get('base_datos');

        $query = $con->prepare($sql);

        $query->bindValye("id", $args['id'], PDO::PARAM_INT); //?Agregar el id a la consulta porque lo eliminamos del body
        $query->execute(;

        $status = $query->rowCount() > 0 ? 200 : 404; //?200 OK, 404 No encontrado
        $query = null;
        $con = null;

        $response->getBody()->write(json_encode($res));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);

    }


}