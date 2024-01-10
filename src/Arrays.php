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
}