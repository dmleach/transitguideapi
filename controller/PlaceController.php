<?php

namespace transitguide\api\controller;

class PlaceController extends base\DataActionController
{
    public function get($request)
    {
        // return $this->model->list();
        echo "<p>This is PlaceController::get</p>";
    }

    public function getModelClassName(): string
    {
        return '\transitguide\api\model\PlaceModel';
    }
}
