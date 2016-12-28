<?php

namespace transitguide\api\controller\base;

abstract class ActionController
{
    protected $frontController = null;

    public abstract function get($request);

    public function setFrontController($controller)
    {
        $this->frontController = $controller;
    }
}
