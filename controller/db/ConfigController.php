<?php

namespace transitguide\api\controller\db;

class ConfigController extends \Noodlehaus\Config
{
    protected $dbName;

    public function __construct($path)
    {
        parent::__construct($path);

        $this->dbName = $this->get('db');

        if (is_null($this->dbName)) {
            throw new \exception('db setting missing from provided config');
        }
    }

    public function getDb($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->get($this->dbName);
        } else {
            return $this->get("{$this->dbName}.{$key}", $default);
        }
    }

    public function hasDb($key)
    {
        return $this->has("{$this->dbName}.{$key}");
    }
}
