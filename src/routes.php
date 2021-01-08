<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

return function (App $app) {
    $container = $app->getContainer();

    $app->post('/login', function (Request $request, Response $response, array $args) {
        $input = $request->getParsedBody();
        $sql = "SELECT * FROM usuarios WHERE usuario= :usuario";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("usuario", $input['usuario']);
        $sth->execute();
        $user = $sth->fetchObject();
        // verify email address.
        if (!$user) {
            return $this->response->withJson(['error' => true, 'message' => 'Usuario o contraseña incorrecta.']);
        }
        // verify password.
        if (!password_verify($input['password'], $user->password)) {
            return $this->response->withJson(['error' => true, 'message' => 'Usuario o contraseña incorrecta.']);
        }
        $settings = $this->get('settings'); // get settings array.
        $token = JWT::encode(['id' => intval($user->idUser), 'usuario' => $user->usuario], $settings['jwt']['secret'], "HS256");
        return $this->response->withJson(['error' => false, 'token' => $token]);
    });

    $app->group('/api', function (\Slim\App $app) {

        // ? Obtener todos los todos de un usuario y status
        $app->get('/todo/{status}/{idUser}', function (Request $request, Response $response, array $args) {
            $res = TodosController::getAllByStatusAndUsuario($args['status'], $args['idUser']);
            $response = $response->withHeader("Content-Type", "application/json")
                ->withStatus(200)
                ->withJson($res);
            return $response;
        });
        // ? Crear todo
        $app->post('/todo', function (Request $request, Response $response) {
            $res = TodosController::postTodo($request->getParsedBody());
            $response = $response->withHeader("Content-Type", "application/json")
                ->withStatus(200)
                ->withJson($res);
            return $response;
        });
        // ? Actualizar usuario
        $app->put('/todo', function (Request $request, Response $response) {
            $res = TodosController::putTodo($request->getParsedBody());
            $response = $response->withHeader("Content-Type", "application/json")
                ->withStatus(200)
                ->withJson($res);
            return $response;
        });
        // ? Eliminar un registro de forma logica
        $app->delete('/todo', function (Request $request, Response $response) {
            $res = TodosController::deleteTodo($request->getParsedBody());
            $response = $response->withHeader("Content-Type", "application/json")
                ->withStatus(200)
                ->withJson($res);
            return $response;
        });
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
        $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
        return $handler($req, $res);
    });
};
