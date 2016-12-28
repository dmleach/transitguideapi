<?php

namespace transitguide\api;

// Composer's autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Load the front controller
$frontController = new controller\FrontController();
// TO-DO: MAKE THIS CONFIGURABLE
$frontController->setConfigFilePath(__DIR__ . '/config.yml');
// TO-DO: MAKE THIS CONFIGURABLE
$frontController->loadRoutes(__DIR__ . '/routing.yml');

// Get the query string, which includes the requested action, provided via the
// rewrite rule in htaccess: RewriteRule ^(.*)$ index.php?action=$1 [QSA,L]
// echo '<pre>' . print_r($_SERVER, true) . '</pre>';
$queryString = array_key_exists('QUERY_STRING', $_SERVER) ? $_SERVER['QUERY_STRING'] : null;

// Parse the query string
$frontController->parseQueryString($queryString, $subject, $verb, $parameters);

// Instantiate the configured controller for the requested subject
$actionController = $frontController->getController($subject);

// Show an error if no controller was found for the action
if ($actionController === false) {
    echo "I could not find a controller for action {$action}";
}

// Execute the controller's verb method
$actionController->$verb($parameters);
