namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductoController
{
    public function read(Request $request, Response $response, $args): Response
    {
        // Lógica para leer el producto (podrías obtenerlo de la base de datos)
        $response->getBody()->write("Leyendo producto con id: " . ($args['id'] ?? 'todos'));
        return $response;
    }

    public function create(Request $request, Response $response, $args): Response
    {
        // Lógica para crear un producto (validación de datos, guardado en la base de datos, etc.)
        $response->getBody()->write("Producto creado!");
        return $response;
    }
}