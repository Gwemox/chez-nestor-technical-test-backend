<?php

namespace App\Helper;

class ArrayHelper
{
    /**
     * Recursively implodes an array with optional key inclusion
     *
     * Example of $include_keys output: key, value, key, value, key, value
     *
     * @access  public
     * @param array $array multi-dimensional array to recursively implode
     * @param string $glue value that glues elements together
     * @param bool $includeKeys include keys before their values
     * @return  string  imploded array
     */
    public static function recursiveImplode(array $array, string $glue = ',', bool $includeKeys = false)
    {
        $gluedString = '';

        // Recursively iterates array and adds key/value to glued string
        array_walk_recursive($array, function ($value, $key) use ($glue, $includeKeys, &$gluedString) {
            $includeKeys and $gluedString .= $key .' : ';
            $gluedString .= $value . $glue;
        });

        // Removes last $glue from string
        strlen($glue) > 0 and $gluedString = substr($gluedString, 0, -strlen($glue));

        return (string)$gluedString;
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  iterable  $array
     * @param  string  $prepend
     * @author Laravel
     * @return array
     */
    public static function dot(iterable $array, string $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }
}