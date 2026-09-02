<?php

namespace LiveControls\Utils;

use Exception;

class Others
{
    /**
     * Calculates formulas with replaceable variables
     *
     * @param string $formula The formula can consist of mathematic operations and variables
     * @param array $variables Variables are made out of a key which will be inside the formula and will be replaced and a value which will be the value the variable in the formula will be replaced
     * @param bool $escapeVars If set to true, PHP variables like $var will be escaped in the formula and will include every $ character (not in variables!)
     * @return int|float|false|null Returns a numeric value of the result of the formula, false if an exception was thrown or null if the formula didn't return a numeric value
     */
    public static function calculateFormulas(string $formula, array $variables, bool $escapeVars = false, array $additionalEscapes = []): int|float|false|null
    {
        $result = floatval($formula);
        if (is_numeric($result)) {
            return $result;
        }
        if($escapeVars == true){
            $result = str_replace("$", "", $result);
        }

        foreach($additionalEscapes as $esc){
            $result = str_replace($esc, "", $result);
        }

        foreach($variables as $var => $val){
            $formula = str_replace($var, $val, $formula);
        }

        try {
            $evaluated = eval('return ('.$formula.');');
            return is_numeric($evaluated) ? $evaluated : null;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Fixes brazilian mobile phone numbers by adding an additional 9 where necessary
     *
     * @param string $number The phone number inclusive the 55 for brazil! Ex. 553112345678
     * @return string
     */
    public static function fixBrazilianMobilePhone(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);
        if (substr($number, 0, 2) == "55") {
            $number = substr($number, 2);
        }

        if (strlen($number) == 10) {
            $areaCode = substr($number, 0, 2);
            $localNumber = substr($number, 2);
            $number = $areaCode.'9'.$localNumber;
        }
        return "55".$number;
    }

    /**
     * Converts a longitude, latitude and radius in kilometers into an array containing the minimum
     * and maximum latitude and longitude boundaries.
     * 
     * @param float $longitude
     * @param float $latitude
     * @param float $radiusInKm
     * @return array{
     *  minLatitude: float,
     *  maxLatitude: float,
     *  minLongitude: float,
     *  maxLongitude: float,
     * }
     * 
     * @example
     * //Find companies within 50km of the users location. You can use any other calculation afterwards, but this is
     * //a good usage example with the Haversine expression.
     * 
     * $latitude = -19.4232;
     * $longitude = -40.2152;
     * $radiusInKm = 50;
     * 
     * $boundaries = self::getCoordinateBoundaries($longitude, $latitude, $radiusInKm);
     * 
     * $companies = Company::whereBetween('latitude', [
     *  $boundaries['minLatitude'],
     *  $boundaries['maxLatitude']
     * ])
     * ->whereBetween('longitude', [
     *  $boundaries['minLongitude'],
     *  $boundaries['maxLongitude']
     * ])
     * ->selectRaw(
     * '(6371 * acos(
     *  cos(radians(?)) *
     *  cos(raians(latitude)) *
     *  cos(radians(longitude) - radians(?)) +
     *  sin(radians(?)) *
     *  sin(radians(latitude
     *  )) AS distance',
     *  [$latitude, $longitude, $latitude],
     * )->having('distance', '<=', $radiusInKm)
     * ->orderBy('distance')
     * ->get();
     */
    public static function getCoordinateBoundaries(float $longitude, float $latitude, float $radiusInKm): array
    {
        $kmPerOneDegreeOfLatitude = 111.32;
        $latitudeDelta = $radiusInKm / $kmPerOneDegreeOfLatitude;
        $longitudeDelta = $radiusInKm / ($kmPerOneDegreeOfLatitude * cos(deg2rad($latitude)));
        $minLatitude = $latitude - $latitudeDelta;
        $maxLatitude = $latitude + $latitudeDelta;
        $minLongitude = $longitude - $longitudeDelta;
        $maxLongitude = $longitude + $longitudeDelta;

        return [
            'minLatitude' => $minLatitude,
            'maxLatitude' => $maxLatitude,
            'minLongitude' => $minLongitude,
            'maxLongitude' => $maxLongitude,
        ];
    }
}
