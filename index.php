<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/middlewares/auth.php';
require_once __DIR__ . '/routes/users.php';
require_once __DIR__ . '/routes/groups.php';
require_once __DIR__ . '/routes/messages.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->setBasePath('');

// Middleware
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Register Routes
registerUserRoutes($app);
registerGroupRoutes($app);
registerMessageRoutes($app);

$app->run();
