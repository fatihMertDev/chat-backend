<?php

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Authenticate user using the provided token.
 * Returns user data if authentication is successful, otherwise null.
 */
function authenticateUser(Request $request): ?array {
    $token = $request->getHeaderLine('Authorization');
    if (!$token) return null;
    $db = getDatabaseConnection();
    $stmt = $db->prepare("SELECT id, username FROM users WHERE token = :token");
    $stmt->execute(['token' => $token]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
