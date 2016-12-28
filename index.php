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

// Get the requested action, which is provided via the rewrite rule in htaccess:
// RewriteRule ^(.*)$ index.php?action=$1 [QSA,L]
$action = array_key_exists('action', $_GET) ? $_GET['action'] : null;

// Parse the action into a subject and verb
$frontController->parseAction($action, $subject, $verb);

// Instantiate the configured controller for the requested subject
$actionController = $frontController->getController($subject);

// Show an error if no controller was found for the action
if ($actionController === false) {
    echo "I could not find a controller for action {$action}";
}

// Execute the controller's verb method
$actionController->$verb();
