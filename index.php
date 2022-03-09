<?php

namespace ceresia_adventure;

require_once 'vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    $file = str_replace('ceresia_adventure\\', '', lcfirst($class_name)) . '.php';
    if (file_exists($file)) {
        require($file);
    } else {
        http_response_code(404);
        exit;
    }
});

$request = $_SERVER['REQUEST_URI'];

$requests = explode('/', $request);

$endpoint = ucfirst($requests[1]);
$method = $requests[2] ?? null;

if (empty($endpoint)) {
    $endpoint = "accueil";
}

$controllerString = "ceresia_adventure\controllers\\" . ucfirst($endpoint) . "Controller";
if (!class_exists($controllerString)) {
    header("HTTP/1.0 404 Not Found");
}
$controller = new $controllerString();

if ((!empty($method) && !method_exists($controller, $method))) {
    http_response_code(404);
    exit;
}

if (empty($method)) {
    $controller->index();
} else {
    $controller->$method();
}
