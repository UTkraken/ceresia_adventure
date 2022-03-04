<?php

abstract class Model {

    protected array $config;

    public function __construct() {
        $this->config = (new Config())->config;
    }

    public static function populate(array $object): ?object
    {
        return null;
    }
}