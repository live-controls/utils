<?php

namespace Helvetiapps\LiveControls\Utils;

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
     * Removed $array[$key] and overwrites the $array with the new array
     *
     * @param [type] $key
     * @param array $array
     * @return void
     */
    public static function array_remove($key, array &$array){
        $newArray = [];
        foreach($array as $oldKey => $oldValue){
            if($oldKey != $key){
                $newArray[$oldKey] = $oldValue;
            }
        }
        $array = $newArray;
    }

    /**
     * Removes the value $value inside $array and overwrites $array with the new array
     *
     * @param [type] $value
     * @param array $array
     * @return void
     */
    public static function array_remove_value($value, array &$array){
        $newArray = [];
        foreach($array as $oldValue){
            if($oldValue != $value){
                array_push($newArray, $oldValue);
            }
        }
        $array = $newArray;
    }
}