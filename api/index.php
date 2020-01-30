<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/admin-user/users.service.php';

use Api\AdminUser\AdminUserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Middleware\ContentLengthMiddleware;
use Slim\Middleware\OutputBufferingMiddleware;
use Slim\Psr7\Factory\StreamFactory;

$app = AppFactory::create();

$streamFactory = new StreamFactory();

/**
 * The two modes available are
 * OutputBufferingMiddleware::APPEND (default mode) - Appends to existing response body
 * OutputBufferingMiddleware::PREPEND - Creates entirely new response body
 */
$mode = OutputBufferingMiddleware::APPEND;
$outputBufferingMiddleware = new OutputBufferingMiddleware($streamFactory, $mode);
$app->add($outputBufferingMiddleware);

// Content header to the response
$contentLengthMiddleware = new ContentLengthMiddleware();
$app->add($contentLengthMiddleware);

// Add BodyParsingMiddleware for BodyParse
$app->addBodyParsingMiddleware();

// Add RoutingMiddleware
$app->addRoutingMiddleware();

// Add MethodOverride middleware
$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->add(function (Request $request, RequestHandler $handler) {
    return $handler->handle($request);
});

$app->get('/api/admin/user', function (Request $request, Response $response, $args) {
    try {
        AdminUserService::show();
        return $response;
    } catch (\Exception $error) {
        http_response_code(500);
        echo json_encode([
            'statusCode' => 500,
            'status' => 'error',
            'message' => $error->getMessage()
        ]);
        return $response;
    }
});

$app->get('/api/admin/user/{id}', function (Request $request, Response $response, $args) {
    try {
        AdminUserService::get($args['id']);
        return $response;
    } catch (\Exception $error) {
        http_response_code(500);
        echo json_encode([
            'statusCode' => 500,
            'status' => 'error',
            'message' => $error->getMessage()
        ]);
        return $response;
    }
});

$app->post('/api/admin/user', function (Request $request, Response $response, $args) {
    try {
        AdminUserService::create($request->getParsedBody());
        return $response;
    } catch (\Exception $error) {
        http_response_code(500);
        echo json_encode([
            'statusCode' => 500,
            'status' => 'error',
            'message' => $error->getMessage()
        ]);
        return $response;
    }
});

$app->put('/api/admin/user/{id}', function (Request $request, Response $response, $args) {
    try {
        AdminUserService::update($args['id'], $request->getParsedBody());
        return $response;
    } catch (\Exception $error) {
        http_response_code(500);
        echo json_encode([
            'statusCode' => 500,
            'status' => 'error',
            'message' => $error->getMessage()
        ]);
        return $response;
    }
});

$app->delete('/api/admin/user/{id}', function (Request $request, Response $response, $args) {
    try {
        AdminUserService::delete($args['id']);
        return $response;
    } catch (\Exception $error) {
        http_response_code(500);
        echo json_encode([
            'statusCode' => 500,
            'status' => 'error',
            'message' => $error->getMessage()
        ]);
        return $response;
    }
});

$app->run();
