<?php

namespace transitguide\api\controller\db\base;

class MongoController
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

    public function insert($collectionName, $documents)
    {
        // TO-DO: FIGURE OUT HOW TO HANDLE DUPLICATES OF EXISTING RECORDS

        // Create a bulk write object, even if only one document is being written
        $write = new \MongoDB\Driver\BulkWrite();

        // Iterate through each of the given documents
        foreach ($documents as $document) {
            // If there's only document in the array, here our variable will
            // contain a string. Place it in an array
            if (!is_array($document)) {
                $document = [$document];
            }

            // Add the insert command to the bulk write
            $write->insert($document);
        }

        // Concatenate the database and collection names
        $collection = $this->databaseName .  '.' . $collectionName;

        // Execute the bulk write and return the result
        $result = $this->manager->executeBulkWrite($collection, $write);

    }
}
