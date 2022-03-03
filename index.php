<?php

require_once 'vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    $controller = 'controllers/' . $class_name . '.php';
    $util = 'utils/' . $class_name . '.php';
    $repository = 'repositories/' . $class_name . '.php';
    $model = 'models/' . $class_name . '.php';

    if (file_exists($controller)) {
        require($controller);
    } elseif (file_exists($util)) {
        require($util);
    } elseif (file_exists($repository)) {
        require($repository);
    } else {
        require ($model);
    }
});

$request = $_SERVER['REQUEST_URI'];

$requests = explode('/', $request);

$endpoint = ucfirst($requests[1]);
$method = $requests[2] ?? null;

if (empty($endpoint)) {
    $endpoint = "accueil";
}

$controllerString = $endpoint . "Controller";
if (!class_exists($controllerString)) {
    http_response_code(404);
}
$controller = new $controllerString();

if ((!empty($method) && !method_exists($controller, $method))) {
    http_response_code(404);
}

if (empty($method)) {
    $controller->index();
} else {
    $controller->$method();
}
