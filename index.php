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

//Get the URI (domain/endpoint/argument)
$request = $_SERVER['REQUEST_URI'];

if(str_contains($request, '.')) {
    header('Location: ' . $request);
}

session_start();

//Create an array from all the data in the URI, separated by '/'
$requests = explode('/', $request);

//Get the URI endpoint as well as potential arguments or methods
$endpoint = ucfirst($requests[1]);
$method = $requests[2] ?? null;

// TODO Passer l'argument en variable $_POST pour le passer dans le contrôleur ?
//$argument = $requests[3] ?? null;

//Redirect to the homepage if the url only contains the domain
if (empty($endpoint)) {
    $endpoint = "accueil";
}

//Use the URI endpoint to fetch the corresponding Controller ('enigma' will search for 'enigmaController')
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
