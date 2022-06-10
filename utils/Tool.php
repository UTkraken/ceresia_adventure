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

    /** Create a link to a specific page. Can be used to create an edit page for a table entry
     * @param        $id
     * @param        $faIcon
     * @param        $url
     * @param        $tooltip
     * @param string $class
     *
     * @return string
     */
    public static function addBtnRedirectDataTable($id, $faIcon, $url, $tooltip, $class = "btn-danger"): string
    {
        return '<a id="' . $id . '" href="' . $url . '" class="btn ' . $class . '" data-toggle="tooltip" title="' . $tooltip . '"><i class="fa ' . $faIcon . '"></i></a>';
    }

    public static function addBtnRedirectEditDataTable($id, $faIcon, $url, $tooltip, $class = "btn-danger"): string
    {
        return '<form action='. $url . ' method="post">
            <input id="id" name="trail_id" type="hidden" value='. $id.'>
             <button type="submit" class="btn btn-default">
                    <div class="btn fa fa-pencil btn-danger btn-s enigma_create"> enigmes</div>
                </button>
            </form>';
    }

    public static function addBtnRedirectTrailEditDataTable($id, $faIcon, $url, $tooltip, $class = "btn-danger"): string
    {
        return '<form action='. $url . ' method="post">
            <input id="id" name="trail_id" type="hidden" value='. $id.'>
             <button type="submit" class="btn btn-default">
                    <div class="btn fa fa-pencil btn-danger btn-s enigma_create"> modif</div>
                </button>
            </form>';
    }

    public static function addBtnDataTable($id, $faIcon, $functionTriggered, $tooltip, $datas = [], $class = "btn-danger"): string
    {
        $data_string = "";
        foreach ($datas as $key => $data) {
            $data_string .= 'data-' . $key . '="' . $data . '" ';
        }
        return '<button id="' . $id . '" data-action="' . $functionTriggered . '" class="btn ' . $class . '" data-toggle="tooltip" title="' . $tooltip . '" ' . $data_string . '><i class="fa ' . $faIcon . '"></i></button>';
    }

    /**
     * @param string $date
     *
     * @return string
     * @throws \Exception
     */
    public static function dateFr(string $date): string
    {
        $date = new DateTime($date);
        return $date->format('d/m/Y');
    }

    /** Return data for specific entity as a json-format string
     * @param $data
     *
     * @return string
     */
    public static function returnForDataTable($data): string
    {
//        var_dump($data);
        return json_encode(['recordsTotal' => count($data), 'recordsFiltered' => count($data), 'data' => $data]);
    }
}
