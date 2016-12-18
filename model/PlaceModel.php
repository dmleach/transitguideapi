<?php

namespace transitguide\api\model;

// TO-DO: This needs to extend a generic database controller instead
class PlaceModel extends base\MongoModel
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
