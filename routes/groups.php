<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

function registerGroupRoutes(App $app) {
    // Endpoint to fetch all chat groups
    $app->post('/groups', function (Request $request, Response $response) {
        $data = json_decode($request->getBody()->getContents(), true);
        $db = getDatabaseConnection();
        $stmt = $db->prepare("INSERT INTO chat_groups (name) VALUES (:name)");
        $stmt->execute(['name' => $data['name']]);
        // Return the list of groups as JSON response
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });
    // Endpoint to allow a user to join a chat group
    $app->post('/groups/{group_id}/join', function (Request $request, Response $response, array $args) {
        $user = authenticateUser($request);
        if (!$user) {
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json')->write(json_encode(['error' => 'Unauthorized']));
        }
        $db = getDatabaseConnection();
        $stmt = $db->prepare("INSERT OR IGNORE INTO group_members (user_id, group_id) VALUES (:user_id, :group_id)");
        $stmt->execute(['user_id' => $user['id'], 'group_id' => $args['group_id']]);
        // Return success response
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    });
    // Endpoint to create a new chat group
    $app->get('/groups', function (Request $request, Response $response) {
        $db = getDatabaseConnection();
        $stmt = $db->query("SELECT * FROM chat_groups");
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($groups));
        return $response->withHeader('Content-Type', 'application/json');
    });
    
}