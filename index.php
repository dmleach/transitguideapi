<?php

namespace transitguide\api;

// Composer's autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Get the requested action, which is provided via the rewrite rule in htaccess:
// RewriteRule ^(.*)$ index.php?action=$1 [QSA,L]
$action = array_key_exists('action', $_GET) ? $_GET['action'] : null;

// Load the routing configuration file
// TO-DO: MAKE THIS CONFIGURABLE
$frontController = new controller\FrontController();
$frontController->loadRoutes(__DIR__ . '/routing.yml');

// Instantiate the configured controller for the requested action
$actionController = $frontController->getController($action);

// Show an error if no controller was found for the action
if ($actionController === false) {
    echo "I could not find a controller for action {$action}";
}
