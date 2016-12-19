<?php

namespace transitguide\api\model\base;

abstract class MongoModel
{
    protected $databaseName;

    // Manager is a final class, so we can't inherit from it
    protected $manager;

    public function __construct(\transitguide\api\controller\db\ConfigController $config)
    {
        $this->databaseName = $config->getDb('database');
        $this->manager = new \MongoDB\Driver\Manager($config->getDb('uri'));
    }

    public function describe()
    {
        return print_r($this->manager, true);
    }

    public function getCollectionName(): string
    {
        // Concatenate the database and collection names
        return $this->databaseName .  '.' . $this->getTableName();
    }

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

    public abstract function getIndexFields(): array;

    public abstract function getTableName(): string;

    // public function insert($collectionName, $documents)
    // {
    //     // TO-DO: FIGURE OUT HOW TO HANDLE DUPLICATES OF EXISTING RECORDS
    //
    //     // Create a bulk write object, even if only one document is being written
    //     $write = new \MongoDB\Driver\BulkWrite();
    //
    //     // Iterate through each of the given documents
    //     foreach ($documents as $document) {
    //         // If there's only document in the array, here our variable will
    //         // contain a string. Place it in an array
    //         if (!is_array($document)) {
    //             $document = [$document];
    //         }
    //
    //         // Add the insert command to the bulk write
    //         $write->insert($document);
    //     }
    //
    //     // Concatenate the database and collection names
    //     $collection = $this->databaseName .  '.' . $collectionName;
    //
    //     // Execute the bulk write and return the result
    //     $result = $this->manager->executeBulkWrite($collection, $write);
    //
    // }

    public function list()
    {
        $query = new \MongoDB\Driver\Query([], []);
        $cursor = $this->manager->executeQuery($this->getCollectionName(), $query);
        $rows = $cursor->toArray();
        $result = [];

        foreach ($rows as $row) {
            $indexFields = [];

            foreach ($row as $field => $value) {
                if (in_array($field, $this->getIndexFields())) {
                    $indexFields[$field] = $value;
                }
            }

            $result[] = $indexFields;
        }

        return $result;
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

            // Add the upsert command to the batch
            $write->update($filter, ['$set' => $row], ['upsert' => true]);
        }

        // Execute the bulk write and return the result
        return $this->manager->executeBulkWrite($this->getCollectionName(), $write);
    }
}
