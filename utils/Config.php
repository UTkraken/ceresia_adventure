<?php

class Config
{
    public array $config = [];
    private const PATH = '.env';

    public function __construct()
    {
        if (!is_readable($this::PATH)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $this::PATH));
        }

        $lines = file($this::PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $this->config)) {
                $this->config[$name] = $value;
            }
        }
    }
}