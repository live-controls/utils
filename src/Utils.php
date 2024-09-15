<?php

namespace LiveControls\Utils;

use Exception;
use Transliterator;

class Utils
{
    /**
     * Counts the amount of numbers inside a number
     * From: https://stackoverflow.com/a/28434327
     *
     * @param integer $num
     * @return void
     */
    public static function countNumber(int $num): int
    {
        return $num !== 0 ? floor(log10($num) + 1) : 1;
    }

    /**
     * Calculates the days between $fromDay and $toDay over a specific month
     *
     * @param integer $fromDay The weekday the calculation should start (0 is sunday)
     * @param integer $toDay The weekday the calculation should end (0 is sunday)
     * @param integer $month The month of the year the calculation should take place in
     * @param integer $year The year the calculation should take place in
     * @return int The amount of days used with this setting
     */
    public static function calculateDaysInMonth(int $fromDay, int $toDay, int $month, int $year): int
    {
        $days = 0;

        $lastday = date("t", mktime(0,0,0,$month,1,$year));
        for($i = 1; $i <= $lastday; $i++)
        {
            if($lastday >= $i)
            {
                $weekday = date("w", mktime(0,0,0,$month, $i, $year));
                if($weekday <= $toDay && $weekday >= $fromDay){
                    $days++;
                }
            }
        }
        return $days;
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
        if (!is_numeric($number)) {
            return false;
        }
        $numero_extenso = '';
        $arr = explode(".", $number);
        $inteiro = $arr[0];
        if (isset($arr[1])) {
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

    public static function number2Currency(float $number, string $locale = 'en', string $currency = null)
    {
        //Check if numberformatter is loaded before trying to access it
        if(!class_exists('NumberFormatter', false))
        {
            throw new Exception('NumberFormatter class is required, but couldn\'t be found!');
        }
        
        $nf = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);

        if(!is_null($currency))
        {
            return $nf->formatCurrency($number, $currency);
        }
        return $nf->format($number);
    }

    public static function array2String(array $array, string $delimiter = ', '): string
    {
        $str = '';
        foreach($array as $key => $value)
        {
            
            $str .= $value;
            if($key != count($array) - 1)
            {
                $str .= $delimiter;
            }
        }
        return $str;
    }

    /**
     * Checks if the string is null or empty
     *
     * @param $str
     * @return boolean
     */
    public static function isNullOrEmpty($str): bool{
        return ($str === null || trim($str) === '');
    }

    /**
     * Calculates formulas with replaceable variables
     *
     * @param string $formula The formula can consist of mathematic operations and variables
     * @param array $variables Variables are made out of a key which will be inside the formula and will be replaced and a value which will be the value the variable in the formula will be replaced
     * @param bool $escapeVars If set to true, PHP variables like $var will be escaped in the formula and will include every $ character (not in variables!)
     * @return int|float|false|null Returns a numeric value of the result of the formula, false if an exception was thrown or null if the formula didn't return a numeric value
     */
    public static function calculateFormulas(string $formula, array $variables, bool $escapeVars = false, array $additionalEscapes = [])
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
     * Removes all non-numeric characters and leading zeros from a string and returns an integer
     *
     * @param string $value
     * @return integer|null Returns null if value can't be made an integer
     */
    public static function toInteger(string $value):int|null
    {
        $value = preg_replace('/\D/', '', $value);
        if(!is_numeric($value)){
            return null;
        }
        return $value;
    }

    /**
     * Removes all non-numeric characters from a string and returns an integer or string if it starts with a 0
     *
     * @param string $value
     * @return int|string
     */
    public static function toNumeric(string $value):int|string|null
    {
        $value = preg_replace('/\D/', '', $value);
        if(!is_numeric($value)){
            return null;
        }
        return $value;
    }

    /**
     * Checks if a string contains a certain needle
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    public static function stringContains(string $haystack, string $needle): bool
    {
        return $needle !== '' && str_contains($haystack, $needle);
    }

    /**
     * Checks if the CPF number is valid
     *
     * @param string $cpf
     * @return boolean
     */
    public static function isValidCPF(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $cpf = ltrim($cpf, '0');
        if (strlen($cpf) !== 11) {
            return false;
        }
        if (preg_match('/(\d)\1{9}/', $cpf)) {
            return false;
        }
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $firstDigit = $remainder < 2 ? 0 : 11 - $remainder;
        if ($cpf[9] !== $firstDigit) {
            return false;
        }
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $secondDigit = $remainder < 2 ? 0 : 11 - $remainder;
        return $cpf[10] === $secondDigit;
    }

    /**
     * Checks if the CNPJ number is valid
     *
     * @param string $cnpj
     * @return boolean
     */
    public static function isValidCNPJ(string $cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        $cnpj = ltrim($cnpj, '0');
        if (strlen($cnpj) !== 14) {
            return false;
        }
        if (preg_match('/(\d)\1{12}/', $cnpj)) {
            return false;
        }
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * (15 - $i);
        }
        $remainder = $sum % 11;
        $firstDigit = $remainder < 2 ? 0 : 11 - $remainder;
        if ($cnpj[12] !== $firstDigit) {
            return false;
        }
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * (16 - $i);
        }
        $remainder = $sum % 11;
        $secondDigit = $remainder < 2 ? 0 : 11 - $remainder;
        return $cnpj[13] === $secondDigit;
    }

    /**
     * Converts string to a string with only latin characters
     *
     * @param string $str
     * @return string
     * @requires php-intl
     */
    public static function toLatin(string $str): string
    {
        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
        return $transliterator->transliterate($str);
    }
}