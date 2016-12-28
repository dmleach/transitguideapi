<?php

namespace transitguide\api\controller;

class FrontController
{
    protected $configController = null;
    protected $paths;

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
        if (array_key_exists($path, $this->paths)) {
            return $this->paths[$path]['controller'];
        } else {
            return false;
        }
    }

    public function loadRoutes($filepath)
    {
        if (file_exists($filepath)) {
            $routingConfig = new \Noodlehaus\Config($filepath);

            foreach ($routingConfig as $path => $config) {
                $this->paths[$path] = $config;
            }
        } else {
            throw new Exception("Cannot find routing file at {$filepath}");
        }
    }

    public function parseQueryString($queryString, &$subject, &$verb, &$parameters)
    {
        $subject = '';


        parse_str($queryString, $arguments);
        // echo '<pre>' . print_r($arguments, true) . '</pre>';

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
            throw new Exception("Cannot find config file at {$filepath}");
        }

        $this->configController = new db\ConfigController($filepath);
    }
}
