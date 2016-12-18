<?php

namespace transitguide\api\controller\import;

class CsvController implements \Iterator
{
    protected $data;

    public function extractFieldValues($data, $fields) {
        if (!is_array($data)) {
            return false;
        }

        $values = [];

        foreach ($data as $idxField => $fieldValue) {
            // The imported data is in a zero-based array, while the field
            // configuration is in a one-based array
            if (array_key_exists($idxField + 1, $fields)) {
                $values[$fields[$idxField + 1]] = $fieldValue;
            }
        }

        return $values;
    }

    public function import($configFilepath)
    {
        $this->data = [];
        $config = new \Noodlehaus\Config($configFilepath);
        $fileHandle = fopen($config->get('file'), 'r');
        $fields = $config->get('fields');

        do {
            $importData = fgetcsv($fileHandle);
            $values = $this->extractFieldValues($importData, $fields);

            if ($values) {
                $this->data[] = $values;
            }
        } while ($importData !== false);
    }

    /**
     * Iterator Methods
     */
    public function current()
    {
        return (is_array($this->data) ? current($this->data) : null);
    }

    public function key()
    {
        return (is_array($this->data) ? key($this->data) : null);
    }

    public function next()
    {
        return (is_array($this->data) ? next($this->data) : null);
    }

    public function rewind()
    {
        return (is_array($this->data) ? reset($this->data) : null);
    }

    public function valid()
    {
        return (is_array($this->data) ? key($this->data) !== null : false);
    }
}
