<?php

namespace transitguide\api\controller;

class PlaceController extends base\DataActionController
{
    public function get($parameters)
    {
        // return $this->model->list();
        echo "<p>This is PlaceController::get</p>";
        echo '<pre>' . print_r($parameters, true) . '</pre>';
    }

    public function getModelClassName(): string
    {
        return '\transitguide\api\model\PlaceModel';
    }
}
