<?php

namespace transitguide\api\controller;

class PlaceController extends base\DataActionController
{
    public function get($parameters)
    {
        // echo "<p>This is PlaceController::get</p>";
        // echo '<pre>' . print_r($parameters, true) . '</pre>';

        return $this->model->select($parameters);
    }

    public function getModelClassName(): string
    {
        return '\transitguide\api\model\PlaceModel';
    }
}
