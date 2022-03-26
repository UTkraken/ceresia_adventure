<?php

namespace ceresia_adventure\utils;

use DateTime;

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

    public static function addBtnRedirectDataTable($id, $faIcon, $url, $tooltip, $class = "btn-danger"): string
    {
        return '<a id="' . $id . '" href="' . $url . '" class="btn ' . $class . '" data-toggle="tooltip" title="' . $tooltip . '"><i class="fa ' . $faIcon . '"></i></a>';
    }

    public static function addBtnDataTable($id, $faIcon, $functionTriggered, $tooltip, $datas = [], $class = "btn-danger"): string
    {
        $data_string = "";
        foreach ($datas as $key => $data) {
            $data_string .= 'data-' . $key . '="' . $data . '" ';
        }
        return '<button id="' . $id . '" data-action="' . $functionTriggered . '" class="btn ' . $class . '" data-toggle="tooltip" title="' . $tooltip . '" ' . $data_string . '><i class="fa ' . $faIcon . '"></i></button>';
    }

    public static function dateFr(string $date): string
    {
        $date = new DateTime($date);
        return $date->format('d/m/Y');
    }

    public static function returnForDataTable($data): string
    {
        return json_encode(['recordsTotal' => count($data), 'recordsFiltered' => count($data), 'data' => $data]);
    }
}