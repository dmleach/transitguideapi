<?php

namespace transitguide\api\controller;

class FrontController
{
    protected $paths;

    public function getController($path)
    {
        $controllerName = $this->getControllerName($path);

        if ($controllerName === false) {
            return false;
        } else {
            return new $controllerName();
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
}
