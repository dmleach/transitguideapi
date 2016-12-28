<?php

namespace transitguide\api\controller;

class PlaceController extends base\DataActionController
{
    public function getModelClassName(): string
    {
        return '\transitguide\api\model\PlaceModel';
    }
}
