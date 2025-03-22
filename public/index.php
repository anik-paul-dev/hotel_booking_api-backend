<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$app = AppFactory::create();

// Add CORS middleware (enhanced for Slim 4)
$app->add(function (Request $request, RequestHandler $handler): Response {
    // Log request details for debugging
    error_log("Request Method: " . $request->getMethod());
    error_log("Request URI: " . $request->getUri());
    error_log("Request Headers: " . json_encode($request->getHeaders()));

    $response = $handler->handle($request);

    // Add CORS headers to all responses
    $response = $response
        ->withHeader('Access-Control-Allow-Origin', '*') // Adjust for production
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Max-Age', '86400'); // Cache preflight for 24 hours

    // Log response details for debugging
    error_log("Response Status: " . $response->getStatusCode());
    error_log("Response Headers: " . json_encode($response->getHeaders()));

    return $response;
});

// Handle OPTIONS requests globally
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    // Log OPTIONS request for debugging
    error_log("Handling OPTIONS request for: " . $request->getUri());
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Max-Age', '86400')
        ->withStatus(200); // Explicitly set OK status
});

// Include and execute routes directly
require __DIR__ . '/../src/routes/api.php';
$routeDefinitions($app); // Explicitly call the closure to register routes

$app->run();