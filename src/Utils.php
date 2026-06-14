<?php

namespace LiveControls\Utils;

use LiveControls\Utils\Enums\SocialSecurityNumberTypes;
use Carbon\Carbon;
use RoundingMode;

class Utils
{
    /**
     * Counts the amount of numbers inside a number
     * From: https://stackoverflow.com/a/28434327
     *
     * @param integer $num
     * @return int
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Numbers::countNumber() instead
     */
    public static function countNumber(int $num): int
    {
        return Numbers::countNumber($num);
    }

    /**
     * Calculates the days between $fromDay and $toDay over a specific month
     *
     * @param integer $fromDay The weekday the calculation should start (0 is sunday)
     * @param integer $toDay The weekday the calculation should end (0 is sunday)
     * @param integer $month The month of the year the calculation should take place in
     * @param integer $year The year the calculation should take place in
     * @return int The amount of days used with this setting
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Time::calculateDaysInMonth() instead
     */
    public static function calculateDaysInMonth(int $fromDay, int $toDay, int $month, int $year): int
    {
        return Time::calculateDaysInMonth($fromDay, $toDay, $month, $year);
    }

    /**
     * Transforms the number in cents to its textform. Needs NumberFormatter for it to work!
     *
     * @param integer $numberInCents
     * @return string A text representation of the number
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Numbers::number2Text() instead
     */
    public static function number2Text(int $numberInCents, string $locale = 'pt_BR'): string
    {
        return Numbers::number2Text($numberInCents, $locale);
    }

    /**
     * Adds leading zeros to a integer value like ID etc.
     *
     * @param integer $value The integer value you want to edit
     * @param integer $length The length of the returned string (missing numbers will be replaced with leading zeros)
     * @param boolean $isMax IF set to true, length will be the max length and an exception will be thrown if number is bigger
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Numbers::leadingZeros() instead
     */
    public static function leadingZeros(int $value, int $length, bool $isMax = false):string
    {
        return Numbers::leadingZeros($value, $length, $isMax);
    }

    /**
     * Formats the number to currency
     *
     * @param float $number
     * @param string $locale
     * @param string|null $currency
     * @return string|false
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Numbers::number2Currency() instead
     */
    public static function number2Currency(float $number, string $locale = 'en', ?string $currency = null): string|false
    {
        return Numbers::number2Currency($number, $locale, $currency);
    }

    /**
     * Converts an array to string with a delimiter
     *
     * @param array $array
     * @param string $delimiter
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Arrays::array_to_string() instead
     */
    public static function array2String(array $array, string $delimiter = ', '): string
    {
        return Arrays::array_to_string($array, $delimiter);
    }

    /**
     * Checks if the string is null or empty
     *
     * @param ?string $str
     * @return boolean
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::isNullOrEmpty() instead
     */
    public static function isNullOrEmpty(?string $str): bool{
        return Str::isNullOrEmpty($str);
    }

    /**
     * Calculates formulas with replaceable variables
     *
     * @param string $formula The formula can consist of mathematic operations and variables
     * @param array $variables Variables are made out of a key which will be inside the formula and will be replaced and a value which will be the value the variable in the formula will be replaced
     * @param bool $escapeVars If set to true, PHP variables like $var will be escaped in the formula and will include every $ character (not in variables!)
     * @return int|float|false|null Returns a numeric value of the result of the formula, false if an exception was thrown or null if the formula didn't return a numeric value
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Others::calculateFormulas() instead
     */
    public static function calculateFormulas(string $formula, array $variables, bool $escapeVars = false, array $additionalEscapes = [])
    {
        return Others::calculateFormulas($formula, $variables, $escapeVars, $additionalEscapes);
    }

    /**
     * Removes all non-numeric characters and leading zeros from a string and returns an integer
     *
     * @param string $value
     * @return integer|null Returns null if value can't be made an integer
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Numbers::toInteger() instead
     */
    public static function toInteger(string $value):int|null
    {
        return Numbers::toInteger($value);
    }

    /**
     * Removes all non-numeric characters from a string and returns an integer or string if it starts with a 0
     *
     * @param string $value
     * @return int|string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Numbers::toNumeric() instead
     */
    public static function toNumeric(string $value):int|string|null
    {
        return Numbers::toNumeric($value);
    }

    /**
     * Checks if a string contains a certain needle
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::stringContains() instead
     */
    public static function stringContains(string $haystack, string $needle): bool
    {
        return Str::stringContains($haystack, $needle);
    }

    /**
     * Formats a numeric only representation 11222333000181 of a CNPJ to a human readable one 11.222.333/0001-81
     *
     * @param string $cnpjNumeric
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\SocialSecurity::formatCNPJ() instead
     */
    public static function formatCNPJ(string $cnpjNumeric): string {
        return SocialSecurity::formatCNPJ($cnpjNumeric);
    }

    /**
     * Formats a numeric only representation 12345678900 of a CPF to a human readable one 123.456.789-00
     *
     * @param string $cpfNumeric
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\SocialSecurity::formatCPF() instead
     */
    public static function formatCPF(string $cpfNumeric): string {
        return SocialSecurity::formatCPF($cpfNumeric);
    }

    /**
     * Checks if the CPF/CNPJ number is valid
     *
     * @param string $cpfCnpj
     * @return boolean
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\SocialSecurity::isValidCPFCNPJ() instead
     */
    public static function isValidCPFCNPJ(string $cpfCnpj): bool
    {
        return SocialSecurity::isValidCPFCNPJ($cpfCnpj);
    }

    /**
     * Checks if the CPF number is valid
     *
     * @param string $cpf
     * @return boolean
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\SocialSecurity::isValidCPF() instead
     */
    public static function isValidCPF(string $cpf): bool
    {
        return SocialSecurity::isValidCPF($cpf);
    }

    /**
     * Checks if the CNPJ number is valid
     *
     * @param string $cnpj
     * @return boolean
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\SocialSecurity::isValidCNPJ() instead
     */
    public static function isValidCNPJ(string $cnpj)
    {
        return SocialSecurity::isValidCNPJ($cnpj);
    }

    /**
     * Returns the SocialSecurityNumberType of the $ssn string
     *
     * @param string $ssn
     * @return SocialSecurityNumberTypes
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\SocialSecurity::getSocialSecurityNumberType() instead
     */
    public static function getSocialSecurityNumberType(string $ssn): SocialSecurityNumberTypes
    {
        return SocialSecurity::getSocialSecurityNumberType($ssn);
    }

    /**
     * Converts string to a string with only latin characters
     *
     * @param string $str
     * @return string
     * @requires php-intl
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::toLatin() instead
     */
    public static function toLatin(string $str): string
    {
        return Str::toLatin($str);
    }

    /**
     * Imports CSV from a file and returns an array with headers as first line
     *
     * @param string $fileName
     * @return array
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\CSV::importCSV() instead
     */
    public static function importCSV(string $fileName)
    {
        return CSV::importCSV($fileName);
    }

    /**
     * Exports an array of data [['Max', '10'], ['Peter', '14']] to a valid CSV string
     *
     * @param array $data
     * @param string $separator
     * @param string $lineEnding
     *
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\CSV::exportCSV() instead
     */
    public static function exportCSV(array $data, string $separator = ",", string $lineEnding = "\n"): string
    {
        return CSV::exportCSV($data, $separator, $lineEnding);
    }

    /**
     * Create an URL based on different parts. It will check if the part starts or ends with / and react to it.
     * At the end it will check if the Url is valid
     *
     * @param string ...$parts
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::urlCombine() instead
     */
    public static function urlCombine(string ...$parts): string
    {
        return Str::urlCombine(...$parts);
    }

    /**
     * Returns the file extension from a mimetype or NULL if mimeType is unknown
     *
     * @param string $mimeType
     * @return string|null
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Files::mimeTypeToExtension() instead
     */
    public static function mimeTypeToExtension(string $mimeType): string|null
    {
        return Files::mimeTypeToExtension($mimeType);
    }

    /**
     * Fetches the extension from the filename and tries to fetch the mimetype or returns null if not found
     *
     * @param string $fileName
     * @return string|null
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Files::fileNameToMimeType() instead
     */
    public static function fileNameToMimeType(string $fileName): string|null
    {
        return Files::fileNameToMimeType($fileName);
    }

    /**
     * Returns the filename and extension (if set) from an URL
     *
     * @param string $url
     * @param boolean $withExtension
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Files::getFilenameFromUrl() instead
     */
    public static function getFilenameFromUrl(string $url, bool $withExtension = true): string
    {
        return Files::getFilenameFromUrl($url, $withExtension);
    }

    /**
     * Returns the extension based on the url
     *
     * @param string $url
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Files::getExtensionFromUrl() instead
     */
    public static function getExtensionFromUrl(string $url): string
    {
        return Files::getExtensionFromUrl($url);
    }

    /**
     * Fixes brazilian mobile phone numbers by adding an additional 9 where necessary
     *
     * @param string $number The phone number inclusive the 55 for brazil! Ex. 553112345678
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Others::fixBrazilianMobilePhone() instead
     */
    public static function fixBrazilianMobilePhone(string $number): string
    {
        return Others::fixBrazilianMobilePhone($number);
    }

    /**
     * Normalizes the string from São Paulo or São Paulo to sao paulo
     *
     * @param string $str
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::normalizeString() instead
     */
    public static function normalizeString(string $str): string
    {
        return Str::normalizeString($str);
    }

    /**
     * Returns the previous timespan as an array 'previousFrom', 'previousTo'. The new values are already in Carbon. If $simple is set to true, the plain difference in days will be returned, so a full month won't return the full month before but 31 days before!
     *
     * @param Carbon $from
     * @param Carbon $to
     * @param boolean $simple
     * @return array
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Time::previousTimespan() instead
     */
    public static function previousTimespan(Carbon $from, Carbon $to, bool $simple = false): array
    {
        return Time::previousTimespan($from, $to, $simple);
    }

    /**
     * Checks if the date string is a valid date
     *
     * @param string $dateString Needs to be in format DD/MM
     * @return boolean
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Time::isValidDate() instead
     */
    public static function isValidDate(string $dateString): bool
    {
        return Time::isValidDate($dateString);
    }

    /**
     * Returns true if the startTime is after the endTime
     *
     * @param string $startTime Needs to be in HH:MM format
     * @param string $endTime Needs to be in HH:MM format
     * @return boolean
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Time::isStartAfterEndTime() instead
     */
    public static function isStartAfterEndTime(string $startTime, string $endTime): bool
    {
        return Time::isStartAfterEndTime($startTime, $endTime);
    }

    /**
     * Divides the value $num safely or returns 0
     *
     * @param int|float $num
     * @param int|float $denom
     * @param RoundingMode|int $prec
     * @return int|float
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Numbers::safeDivide() instead
     */
    public static function safeDivide(int|float $num, int|float $denom, RoundingMode|int $prec): int|float
    {
        return Numbers::safeDivide($num, $denom, $prec);
    }

    /**
     * Removes the tag from a string with or without endtag '\</tag>'
     *
     * @param string $string
     * @param string $tag
     * @param boolean $withEndTag
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::stripTag() instead
     */
    public static function stripTag(string $string, string $tag, bool $withEndTag = false): string
    {
        return Str::stripTag($string, $tag, $withEndTag);
    }

    /**
     * Removes all tags from a string with or without endtag '\</tag>'
     *
     * @param string $string
     * @param array $tags
     * @param bool $withEndTags
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::stripTags() instead
     */
    public static function stripTags(string $string, array $tags, bool $withEndTags = false): string
    {
        return Str::stripTags($string, $tags, $withEndTags);
    }

    /**
     * Replaces a tag with a certain string
     *
     * @param string $string
     * @param string $tag
     * @param string $replacement
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::replaceTag() instead
     */
    public static function replaceTag(string $string, string $tag, string $replacement):string
    {
        return Str::replaceTag($string, $tag, $replacement);
    }

    /**
     * Returns an array of dates based on the startDate and the payment periods.
     * Example: 30/60/90 would return a Carbon for 30, 60 and 90 days later.
     * 
     * @param Carbon $startDate
     * @param string $periodString
     * @return array
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Time::periodToDates() instead
     */
    public static function periodToDates(Carbon $startDate, string $periodString): array
    {
        return Time::periodToDates($startDate, $periodString);
    }

    /**
     * Converts to MD format from a html string.
     *
     * @param string $html
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::convertHtmlToMD() instead
     */
    public static function convertHtmlToMD(string $html): string
    {
        return Str::convertHtmlToMD($html);
    }

    /**
     * Will normalize the following values: string, int, float, Carbon to a normal carbon value
     *
     * @param string|int|float|Carbon $value
     * @return Carbon
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Time::normalizeToCarbon() instead
     */
    public static function normalizeToCarbon(string|int|float|Carbon $value): Carbon
    {
        return Time::normalizeToCarbon($value);
    }

    /**
     * Converts seconds into a readable string. Uses the format from $formatString.
     *
     * @param int $seconds
     * @param string $formatString
     * @return string
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Time::secondsToTime() instead
     */
    public static function secondsToTime(int $seconds, string $formatString = '%a days, %h hours, %i minutes and %s seconds'): string
    {
        return Time::secondsToTime($seconds, $formatString);
    }

    /**
     * Tries to convert a value into a string or returns null if it wasn't possible.
     *
     * @param mixed $value
     * @return string|null
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::safeString() instead
     */
    public static function safeString(mixed $value): ?string
    {
        return Str::safeString($value);
    }

    /**
     * Tries to fetch a string out of an array with its key and returns null if it can't be converted or if
     * the key does not exist.
     *
     * @param array $array
     * @param string $key
     * @return string|null
     * 
     * @deprecated v2.0 - Use \LiveControls\Utils\Str::safeStringFromArray() instead
     */
    public static function safeStringFromArray(array $array, string $key): ?string
    {
        return Str::safeStringFromArray($array, $key);
    }

}
