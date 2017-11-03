<?php

namespace App\Helper;

class StringTransformer
{
    /**
     * @param string $offset
     *
     * @return string
     */
    public static function toCamelCase(string $offset): string
    {
        $parts = explode('_', $offset);
        array_walk($parts, function (&$offset) {
            $offset = ucfirst($offset);
        });

        return implode('', $parts);
    }

    /**
     * @param string $offset
     * @param string $splitter
     *
     * @return string
     */
    public static function toSnakeCase(string $offset, string $splitter = '_'): string
    {
        $offset = preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter . '$0', $offset));

        return strtolower($offset);
    }
}