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

    public function parseAction($action, &$subject, &$verb)
    {
        $subject = '';

        // By default, requests are assumed to be GETs
        $verb = 'get';

        // The subject of the action is the first section of the action string, from
        // the beginning to the first slash
        $firstSlashPos = strpos($action, '/');

        if ($firstSlashPos === false) {
            $subject = $action;
        } else {
            $subject = substr($action, 0, $firstSlashPos);

            // The verb of the action is the second section of the action string, from
            // the first slash to the second
            $secondSlashPos = strpos($action, '/', $firstSlashPos + 1);

            if ($secondSlashPos === false) {
                $verb = substr($action, $firstSlashPos + 1);
            } else {
                $verb = substr($action, $firstSlashPos + 1, $secondSlashPos - $firstSlashPos - 1);
            }
        }
    }

    public function setConfigFilePath(string $filepath)
    {
        if (!file_exists($filepath)) {
            throw new Exception("Cannot find config file at {$filepath}");
        }

        $this->configController = new db\ConfigController($filepath);
    }
}
