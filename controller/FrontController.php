<?php

namespace transitguide\api\controller;

class FrontController
{
    const CONFIG_KEY_CONTROLLER = 'controller';

    protected $configController = null;
    protected $paths;

    public function addPath(string $path, array $config): bool
    {
        // Check to see if the given path already exists
        if ($this->getControllerName($path) !== false) {
            return false;
        }

        // Check to see that the given config array contains a controller key
        if (!array_key_exists(self::CONFIG_KEY_CONTROLLER, $config)) {
            return false;
        }

        $this->paths[$path] = $config;
        return true;
    }

    public function getController($path)
    {
        $controllerName = $this->getControllerName($path);

        if ($controllerName === false) {
            return false;
        } else {
            return new $controllerName($this->configController);
        }
    }

    public function getControllerName($path)
    {
        if (!is_array($this->paths)) {
            return false;
        }

        if (!array_key_exists($path, $this->paths)) {
            return false;
        }

        return $this->paths[$path]['controller'];
    }

    public function loadRoutes($filepath)
    {
        if (file_exists($filepath)) {
            $routingConfig = new \Noodlehaus\Config($filepath);

            foreach ($routingConfig as $path => $config) {
                $this->addPath($path, $config);
            }
        } else {
            throw new \Exception("Cannot find routing file at {$filepath}");
        }
    }

    public function parseQueryString($queryString, &$subject, &$verb, &$parameters)
    {
        $subject = '';

        parse_str($queryString, $arguments);

        if (array_key_exists('action', $arguments)) {
            // The subject of the action is the first section of the string,
            // from the beginning to the slash
            $slashPos = strpos($arguments['action'], '/');

            if ($slashPos === false) {
                $subject = $arguments['action'];
            } else {
                $subject = substr($arguments['action'], 0, $slashPos);

                // The verb of the action is the second section of the action
                // string, from the slash to the end
                $verb = substr($arguments['action'], $slashPos + 1);
            }
        }

        // By default, requests are assumed to be GETs
        if ($verb == '') {
            $verb = 'get';
        }

        $parameters = $arguments;
        unset($parameters['action']);
    }

    public function setConfigFilePath(string $filepath)
    {
        if (!file_exists($filepath)) {
            throw new \Exception("Cannot find config file at {$filepath}");
        }

        $this->configController = new db\ConfigController($filepath);
    }
}
