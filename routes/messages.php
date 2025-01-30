<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

function registerMessageRoutes(App $app) {
    $app->post('/messages', function (Request $request, Response $response) {
        // Endpoint to send a message in a chat group
        $user = authenticateUser($request);
        if (!$user) {
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json')->write(json_encode(['error' => 'Unauthorized']));
        }
        $data = json_decode($request->getBody()->getContents(), true);
        $db = getDatabaseConnection();
        $stmt = $db->prepare("INSERT INTO messages (user_id, group_id, message) VALUES (:user_id, :group_id, :message)");
        $stmt->execute(['user_id' => $user['id'], 'group_id' => $data['group_id'], 'message' => $data['message']]);
         // Return success response
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $app->get('/groups/{group_id}/messages', function (Request $request, Response $response, array $args) {
        $db = getDatabaseConnection();
        $stmt = $db->prepare("SELECT messages.id, users.username, messages.message, messages.timestamp 
                              FROM messages 
                              JOIN users ON messages.user_id = users.id 
                              WHERE messages.group_id = :group_id 
                              ORDER BY messages.timestamp ASC");
        $stmt->bindValue(':group_id', $args['group_id'], PDO::PARAM_INT);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($messages));
        return $response->withHeader('Content-Type', 'application/json');
    });
    
}
