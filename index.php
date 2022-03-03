<?php

require_once 'vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    $classToAutoLoad = ['controllers', 'utils', 'repositories', 'models', 'framework'];

    foreach ($classToAutoLoad as $class_directory) {
        $file = $class_directory . '/' . $class_name . '.php';
        if (file_exists($file)) {
            require($file);
        }
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
