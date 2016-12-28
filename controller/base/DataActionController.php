<?php

namespace transitguide\api\controller\base;

abstract class DataActionController extends ActionController
{
    protected $model;

    public function __construct(\transitguide\api\controller\db\ConfigController $config)
    {
        $modelClassName = $this->getModelClassName();
        $this->model = new $modelClassName($config);
    }

    public abstract function getModelClassName(): string;
}
