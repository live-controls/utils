<?php

namespace LiveControls\Utils;

class Arrays
{
    /**
     * Returns the value of $array[$key] or the $default value
     *
     * @param [type] $key
     * @param array $array
     * @param [type] $default
     * @return mixed
     */
    public static function array_get($key, array $array, $default = null) : mixed{
        if(array_key_exists($key, $array)){
            return $array[$key];
        }
        return $default;
    }

    /**
     * Removes $array[$key] and overwrites the $array with a new array
     *
     * @param mixed $keyValue
     * @param array $array
     * @param bool $isValue if true it will internally call array_remove_value()
     * @return void
     */
    public static function array_remove(mixed $keyValue, array &$array, bool $isValue = false){
        $newArray = [];
        if($isValue){
            return static::array_remove_value($keyValue, $array);
        }
        foreach($array as $oldKey => $oldValue){
            if($oldKey != $keyValue){
                $newArray[$oldKey] = $oldValue;
            }
        }
        $array = $newArray;
    }

    /**
     * Removes the value $value inside $array and overwrites $array with the new array
     *
     * @param mixed $value
     * @param array $array
     * @return void
     */
    public static function array_remove_value(mixed $value, array &$array){
        $newArray = [];
        foreach($array as $oldValue){
            if($oldValue != $value){
                array_push($newArray, $oldValue);
            }
        }
        $array = $newArray;
    }

    /**
     * Checks if the array $array has duplicate values inside it
     *
     * @param array $array
     * @param boolean $strict
     * @return boolean
     */
    public static function array_has_duplicates(array $array, bool $strict = false):bool{
        return count(static::array_get_duplicates($array, $strict)) > 0;
    }

    /**
     * Returns all duplicate values as array
     *
     * @param array $array
     * @param boolean $strict
     * @return array
     */
    public static function array_get_duplicates(array $array, bool $strict = false):array{
        $dupes = [];
        $vals = [];
        foreach($array as $value)
        {
            if(in_array($value, $vals, $strict) && !in_array($value, $dupes, $strict)){
                array_push($dupes, $value);
            }
            else if(!in_array($value, $vals, $strict)){
                array_push($vals, $value);
            }
        }
        return $dupes;
    }

    public static function array_remove_duplicates(array $array, bool $strict = false): array
    {
        $newArr = [];
        $dupes = [];
        $vals = [];
        foreach($array as $value)
        {
            if(in_array($value, $vals, $strict) && !in_array($value, $dupes, $strict)){
                array_push($dupes, $value);
            }
            else if(!in_array($value, $vals, $strict)){
                array_push($vals, $value);
            }
        }
        return $dupes;
    }

    /**
     * Gets the cartesian combinations from a list of arrays [[1,2], [3]] would return [[1,3], [2,3]]
     *
     * @param array $arrays
     * @return array
     */
    public static function array_cartesian(array $arrays): array
    {
        if (!$arrays) {
            return array(array());
        }

        $subset = array_shift($arrays);
        $subArrays = static::array_cartesian($arrays);

        $result = array();
        foreach ($subset as $value) {
            foreach ($subArrays as $p) {
                array_unshift($p, $value);
                $result[] = $p;
            }
        }

        return $result;
    }
}