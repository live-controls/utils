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
}
