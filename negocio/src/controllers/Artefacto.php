<?php
    namespace App\controllers;

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Container\ContainerInterface;

    use PDO;

    class Artefacto extends ServicioCURL{
        protected $container;
        private const ENDPOINT= '/artefacto';

        public function __construct(ContainerInterface $c){
            $this->container = $c;
        }

        public function read(Request $request, Response $response, $args){
          
            $url= $this::ENDPOINT . '/read';
            if(isset($args['id'])){
                $url .= '/'.$args['id'];
            }
            //die($url);
            $respA = $this->ejecutarCURL($url, 'GET');

            $response->getbody()->write($respA['resp']);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($respA['status']);
        }

        public function create(Request $request, Response $response, $args){
            
            $body= $request->getBody();
            
            $respA = $this->ejecutarCURL($this::ENDPOINT, 'POST', $body);

            return $response ->withStatus($respA['status']);
        }

        public function update(Request $request, Response $response, $args){
            $body= json_decode($request->getBody());
           


            return $response ->withStatus($status);
        }

        public function delete(Request $request, Response $response, $args){
            

            $sql = "DELETE FROM artefacto WHERE id = :id;";
            $con=  $this->container->get('base_datos');

            $query = $con->prepare($sql);
 
            $query->bindValue('id', $args['id'], PDO::PARAM_INT);
            $query->execute();
              
            $status= $query->rowCount()> 0 ? 200 : 404;
 
            $query=null;
            $con=null;
 
            return $response ->withStatus($status);
        }

        public function filtrar(Request $request, Response $response, $args){
            $datos= $request->getQueryParams();

    

            $sql= "SELECT * FROM artefacto WHERE ";
            foreach($datos as $key => $value){
                $sql .= "$key LIKE :$key AND ";     
            }
            $sql= rtrim($sql, 'AND '). ";";
            

            $con=  $this->container->get('base_datos');
            $query = $con->prepare($sql);

            foreach($datos as $key => $value){
               $query->bindValue(":$key", "%$value%", PDO::PARAM_STR);
            }

            $query->execute();
            
            $res= $query->fetchAll();

            $status= $query->rowCount()> 0 ? 200 : 204;

            $query=null;
            $con=null;


            $response->getbody()->write(json_encode($res));


            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($status);
        }

    }