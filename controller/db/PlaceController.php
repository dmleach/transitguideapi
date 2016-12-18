<?php

namespace transitguide\api\controller\db;

// TO-DO: This needs to extend a generic database controller instead
class PlaceController extends base\MongoController
{
    public function getFilter($row): array
    {
        $filter = [];

        foreach ($row as $field => $value) {
            if (in_array($field, $this->getIndexFields())) {
                $filter[$field] = $value;
            }
        }

        return $filter;
    }

    public function getIndexFields(): array
    {
        return ['name', 'city'];
    }

    public function getNonIndexValues($row): array
    {
        $values = [];

        foreach ($row as $field => $value) {
            if (!in_array($field, $this->getIndexFields())) {
                $values[$field] = $value;
            }
        }

        return $values;
    }

    public function getTableName(): string
    {
        return 'places';
    }

    public function upsert($rows)
    {
        // Create a bulk write object, even if only one document is being written
        $write = new \MongoDB\Driver\BulkWrite();

        // Look at the first element of the given rows. If it isn't an array,
        // then assume we've just been given data for one row and place it in
        // an outer array for compatibility
        if (!is_array(reset($rows))) {
            $rows = [$rows];
        }

        foreach($rows as $row) {
            // The filter consists of the field in the table's index
            $filter = $this->getFilter($row);

            // // Any other fields end up here
            // $values = $this->getNonIndexValues($row);
            //
            // if ($values == []) {
            //     // If only fields in the index were given, provide them as the
            //     // values to write
            //     $values = $filter;
            // }

            // Add the upsert command to the batch
            $write->update($filter, ['$set' => $row], ['upsert' => true]);
        }

        // Concatenate the database and collection names
        $collection = $this->databaseName .  '.' . $this->getTableName();

        // Execute the bulk write and return the result
        $result = $this->manager->executeBulkWrite($collection, $write);

        print_r($result);
    }
}
