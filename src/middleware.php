<?php

use Slim\App;

return function (App $app) {

    $app->add(new \Tuupola\Middleware\JwtAuthentication([
        "path" => $_ENV['JWT_PATH'], /* or ["/api", "/admin"] */
        "header" => $_ENV['JWT_HEADER'],
        "attribute" => $_ENV['JWT_ATTRIBUTE'],
        "secret" => $_ENV['JWT_SECRET'],
        "algorithm" => [$_ENV['JWT_ALGORITHM']],
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]));

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    
    $app->add(function ($req, $res, $next) {
        $response = $next($req, $res);
        return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-API-KEY')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });
};
