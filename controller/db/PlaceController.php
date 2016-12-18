<?php

namespace transitguide\api\controller\db;

// TO-DO: This needs to extend a generic database controller instead
class PlaceController extends base\MongoController
{
    public function getIndexFields(): array
    {
        return ['name', 'city'];
    }

    public function getTableName(): string
    {
        return 'places';
    }
}
