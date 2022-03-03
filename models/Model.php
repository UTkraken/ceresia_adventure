<?php

abstract class Model {

    protected array $config;

    public function __construct() {
        $this->config = (new Config())->config;
    }
}