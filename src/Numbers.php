<?php

namespace LiveControls\Utils;

use Exception;
use RoundingMode;

class Numbers
{
    /**
     * Counts the amount of numbers inside a number
     * From: https://stackoverflow.com/a/28434327
     *
     * @param integer $num
     * @return int
     */
    public static function countNumber(int $num): int
    {
        return $num !== 0 ? floor(log10($num) + 1) : 1;
    }
    
    /**
     * Transforms the number in cents to its textform. Needs NumberFormatter for it to work!
     *
     * @param integer $numberInCents
     * @return string A text representation of the number
     */
    public static function number2Text(int $numberInCents, string $locale = 'pt_BR'): string
    {
        $number = $numberInCents / 100;

        $numero_extenso = '';
        $arr = explode(".", $number);
        $inteiro = $arr[0];
        if(isset($arr[1])){
            $decimos = strlen($arr[1]) == 1 ? $arr[1] . '0' : $arr[1];
        }

        //Check if numberformatter is loaded before trying to access it
        if(!class_exists('NumberFormatter', false))
        {
            throw new Exception('NumberFormatter class is required, but couldn\'t be found!');
        }

        $fmt = new \NumberFormatter($locale, \NumberFormatter::SPELLOUT);
        if (is_array($arr)) {
            $numero_extenso = $fmt->format($inteiro) . ' reais';
            if (isset($decimos) && $decimos > 0) {
                $numero_extenso .= ' e ' . $fmt->format($decimos) . ' centavos';
            }
        }
        return $numero_extenso;
    }

    /**
     * Adds leading zeros to a integer value like ID etc.
     *
     * @param integer $value The integer value you want to edit
     * @param integer $length The length of the returned string (missing numbers will be replaced with leading zeros)
     * @param boolean $isMax IF set to true, length will be the max length and an exception will be thrown if number is bigger
     * @return string
     */
    public static function leadingZeros(int $value, int $length, bool $isMax = false):string
    {
        $value = strval($value);
        $valueLength = strlen($value);
        if($valueLength > $length && $isMax){
            throw new Exception('Value has more numbers than max length!');
        }
        if($valueLength >= $length){
            return (string)$value;
        }

        $valueArr = str_split($value);

        $diff = $length - $valueLength;
        $newValue = '';
        for($i = 0; $i < $length; $i++){
            if($i < $diff){
                $newValue .= '0';
            }else{
                $newValue .= $valueArr[$i - $diff];
            }
        }
        return $newValue;
    }

    /**
     * Removes all non-numeric characters and leading zeros from a string and returns an integer
     *
     * @param string $value
     * @return integer|null Returns null if value can't be made an integer
     */
    public static function toInteger(string $value): int|null
    {
        $value = preg_replace('/\D/', '', $value);
        if(!is_numeric($value)){
            return null;
        }
        return (int)$value;
    }

    /**
     * Removes all non-numeric characters from a string and returns an integer or string if it starts with a 0
     *
     * @param string $value
     * @return int|string
     */
    public static function toNumeric(string $value): int|string|null
    {
        $value = preg_replace('/\D/', '', $value);
        if(!is_numeric($value)){
            return null;
        }
        return $value;
    }

    /**
     * Divides the value $num safely or returns 0
     *
     * @param int|float $num
     * @param int|float $denom
     * @param RoundingMode|int $prec
     * @return int|float
     */
    public static function safeDivide(int|float $num, int|float $denom, RoundingMode|int $prec): int|float
    {
        return $denom > 0 ? round($num / $denom, $prec) : 0;
    }

    public static function number2Currency(float $number, string $locale = 'en', ?string $currency = null): string|false
    {
        //Check if numberformatter is loaded before trying to access it
        if(!class_exists('\NumberFormatter', false))
        {
            throw new Exception('\NumberFormatter class is required, but couldn\'t be found!');
        }

        $nf = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);

        if(!is_null($currency))
        {
            return $nf->formatCurrency($number, $currency);
        }
        return $nf->format($number);
    }

}