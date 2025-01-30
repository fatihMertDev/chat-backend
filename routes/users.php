<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

function registerUserRoutes(App $app) {
    $app->post('/users', function (Request $request, Response $response) {
         
        // Extract user data from the request
        $data = json_decode($request->getBody()->getContents(), true);
        if (!isset($data['username']) || empty($data['username'])) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')
                            ->write(json_encode(['error' => 'Username is required']));
        }
        
        // Generate a unique authentication token
        $db = getDatabaseConnection();
        $token = bin2hex(random_bytes(16));
        $stmt = $db->prepare("INSERT INTO users (username, token) VALUES (:username, :token)");
        $stmt->execute(['username' => $data['username'], 'token' => $token]);
        
        // Return the generated token
        $response->getBody()->write(json_encode(['token' => $token]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });
}