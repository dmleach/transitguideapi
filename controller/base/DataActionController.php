<?php

namespace transitguide\api\controller\base;

abstract class DataActionController extends ActionController
{
    protected $model;

    public function __construct(\transitguide\api\controller\db\ConfigController $config)
    {
        // Get the name of this controller's model class from the child
        $modelClassName = $this->getModelClassName();

        // Use the provided database configuration to instantiate the model
        $this->model = new $modelClassName($config);
    }

    public function get($parameters)
    {
        // Call either the select or list function, depending on whether any
        // parameters were provided 
        if ($parameters) {
            return $this->model->select($parameters);
        } else {
            return $this->model->list();
        }
    }

    public abstract function getModelClassName(): string;
}
