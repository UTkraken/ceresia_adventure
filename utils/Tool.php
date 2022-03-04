<?php

class Tool
{
    public static function camelCaseToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', lcfirst($input)));

    }

    public static function snakeCaseToCamelCase(string $input): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

    public static function addSToSnakeCase(string $input): string
    {
        $words = explode('_', $input);
        foreach ($words as $key => $word) {
            $words[$key] .= 's';
        }
        return implode('_', $words);
    }
}