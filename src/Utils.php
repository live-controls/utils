<?php

namespace LiveControls\Utils;

use Carbon\Carbon;
use Exception;
use InvalidArgumentException;
use RoundingMode;
use RuntimeException;
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

    public static function number2Currency(float $number, string $locale = 'en', ?string $currency = null)
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

    /**
     * Converts an array to string with a delimiter
     *
     * @param array $array
     * @param string $delimiter
     * @return string
     */
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
     * Formats a numeric only representation 11222333000181 of a CNPJ to a human readable one 11.222.333/0001-81
     *
     * @param string $cnpjNumeric
     * @return string
     */
    public static function formatCNPJ(string $cnpjNumeric): string {
        $cnpjNumeric = static::toNumeric($cnpjNumeric);
        $cnpjStr = str_pad($cnpjNumeric, 14, '0', STR_PAD_LEFT);
        if (strlen($cnpjStr) !== 14 || !ctype_digit($cnpjStr)) {
            throw new Exception("Invalid CNPJ!");
        }
        return substr($cnpjStr, 0, 2) . '.' . substr($cnpjStr, 2, 3) . '.' . substr($cnpjStr, 5, 3) . '/' . substr($cnpjStr, 8, 4) . '-' . substr($cnpjStr, 12, 2);
    }

    /**
     * Formats a numeric only representation 12345678900 of a CPF to a human readable one 123.456.789-00
     *
     * @param string $cpfNumeric
     * @return string
     */
    public static function formatCPF(string $cpfNumeric): string {
        $cpfNumeric = static::toNumeric($cpfNumeric);
        $cpfStr = str_pad($cpfNumeric, 11, '0', STR_PAD_LEFT);
        if (strlen($cpfStr) !== 11 || !ctype_digit($cpfStr)) {
            throw new Exception("Invalid CPF!");
        }
        return substr($cpfStr, 0, 3) . '.' . substr($cpfStr, 3, 3) . '.' . substr($cpfStr, 6, 3) . '-' . substr($cpfStr, 9, 2);
    }

    /**
     * Checks if the CPF/CNPJ number is valid
     *
     * @param string $cpfCnpj
     * @return boolean
     */
    public static function isValidCPFCNPJ(string $cpfCnpj): bool
    {
        return static::isValidCPF($cpfCnpj) || static::isValidCNPJ($cpfCnpj);
    }

    /**
     * Checks if the CPF number is valid
     *
     * @param string $cpf
     * @return boolean
     */
    public static function isValidCPF(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf); // Remove non-numeric characters
        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false; // Invalid length or repeated digits (00000000000, 11111111111, etc.)
        }

        // First digit calculation
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$cpf[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $firstDigit = ($remainder < 2) ? 0 : 11 - $remainder;
        if ((int)$cpf[9] !== $firstDigit) {
            return false;
        }

        // Second digit calculation
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int)$cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $secondDigit = ($remainder < 2) ? 0 : 11 - $remainder;

        return (int)$cpf[10] === $secondDigit;
    }

    /**
     * Checks if the CNPJ number is valid
     *
     * @param string $cnpj
     * @return boolean
     */
    public static function isValidCNPJ(string $cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj); // Remove non-numeric characters
        if (strlen($cnpj) !== 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Calculation of the first verification digit
        $weightsFirst = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weightsFirst[$i];
        }
        $remainder = $sum % 11;
        $firstDigit = ($remainder < 2) ? 0 : 11 - $remainder;
        if ((int) $cnpj[12] !== $firstDigit) {
            return false;
        }

        // Calculation of the second verification digit
        $weightsSecond = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weightsSecond[$i];
        }
        $remainder = $sum % 11;
        $secondDigit = ($remainder < 2) ? 0 : 11 - $remainder;
        return (int) $cnpj[13] === $secondDigit;
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

    /**
     * Imports CSV from a file and returns an array with headers as first line
     *
     * @param string $fileName
     * @return array
     */
    public static function importCSV(string $fileName)
    {
        $csv = array_map('str_getcsv', file($fileName));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        return $csv;
    }

    /**
     * Exports an array of data [['Max', '10'], ['Peter', '14']] to a valid CSV string
     *
     * @param array $data
     * @param string $separator
     * @param string $lineEnding
     *
     * @return string
     */
    public static function exportCSV(array $data, string $separator = ",", string $lineEnding = "\n"): string
    {
        $csvString = '';
        foreach($data as $row)
        {
            $line = '';
            foreach($row as $column){
                if($line != ''){
                    $line .= $separator;
                }
                $line .= $column;
            }
            $csvString .= $line.$lineEnding;
        }
        return $csvString;
    }

    /**
     * Create an URL based on different parts. It will check if the part starts or ends with / and react to it.
     * At the end it will check if the Url is valid
     *
     * @param string ...$parts
     * @return string
     */
    public static function urlCombine(string ...$parts): string
    {
        $url = "";
        foreach($parts as $part)
        {
            if($url == ""){
                if(!str_starts_with($part, 'http://') && !str_starts_with($part, 'https://') && !str_starts_with($part, 'ftp://')){
                    $part = "https://".$part;
                }
                $url = $part;
                continue;
            }
            if(!str_starts_with($part, '/')){
                $part = '/'.$part;
            }
            if(str_ends_with($part, '/')){
                $part = substr($part, 0, -1);
            }
            $url .= $part;
        }
        return $url;
    }

    /**
     * Returns the file extension from a mimetype or NULL if mimeType is unknown
     *
     * @param string $mimeType
     * @return string|null
     */
    public static function mimeTypeToExtension(string $mimeType): string|null
    {
        $map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpg'                                                                => 'jpeg',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'audio/mp4'                                                                 => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'font/otf'                                                                  => 'otf',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'font/ttf'                                                                  => 'ttf',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'image/webp'                                                                => 'webp',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'font/woff'                                                                 => 'woff',
            'font/woff2'                                                                => 'woff2',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh'
        ];

        return $map[$mimeType] ?? null;
    }

    /**
     * Fetches the extension from the filename and tries to fetch the mimetype or returns null if not found
     *
     * @param string $fileName
     * @return string|null
     */
    public static function fileNameToMimeType(string $fileName): string|null
    {
        $map = [
            '3g2' => 'video/3gpp2',
            '3gp' => 'video/3gp',
            '7zip' => 'application/x-compressed',
            'aac' => 'audio/x-acc',
            'ac3' => 'audio/ac3',
            'ai' => 'application/postscript',
            'aif' => 'audio/x-aiff',
            'au' => 'audio/x-au',
            'avi' => 'video/avi',
            'bin' => 'application/x-binary',
            'bmp' => 'image/bmp',
            'cdr' => 'application/cdr',
            'cpt' => 'application/mac-compactpro',
            'crl' => 'application/pkix-crl',
            'crt' => 'application/pkix-cert',
            'css' => 'text/css',
            'csv' => 'application/vnd.msexcel',
            'dcr' => 'application/x-director',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dvi' => 'application/x-dvi',
            'eml' => 'message/rfc822',
            'exe' => 'application/x-msdownload',
            'f4v' => 'video/x-f4v',
            'flac' => 'audio/x-flac',
            'flv' => 'video/x-flv',
            'gif' => 'image/gif',
            'gpg' => 'application/gpg-keys',
            'gtar' => 'application/x-gtar',
            'gzip' => 'application/x-gzip',
            'hqx' => 'application/mac-binhex',
            'html' => 'text/html',
            'ico' => 'image/x-icon',
            'ics' => 'text/calendar',
            'jar' => 'application/x-jar',
            'jp2' => 'image/jp2',
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpg',
            'js' => 'application/x-javascript',
            'json' => 'text/json',
            'kml' => 'application/vnd.google-earth.kml+xml',
            'kmz' => 'application/vnd.google-earth.kmz',
            'log' => 'text/x-log',
            'm4a' => 'audio/mp4',
            'm4u' => 'application/vnd.mpegurl',
            'mid' => 'audio/midi',
            'mif' => 'application/vnd.mif',
            'mov' => 'video/quicktime',
            'movie' => 'video/x-sgi-movie',
            'mp3' => 'audio/mp3',
            'mp4' => 'video/mp4',
            'mpeg' => 'video/mpeg',
            'oda' => 'application/oda',
            'ogg' => 'audio/ogg',
            'otf' => 'font/otf',
            'p10' => 'application/pkcs10',
            'p12' => 'application/x-pkcs12',
            'p7a' => 'application/x-pkcs7-signature',
            'p7c' => 'application/pkcs7-mime',
            'p7r' => 'application/x-pkcs7-certreqresp',
            'p7s' => 'application/pkcs7-signature',
            'pdf' => 'application/pdf',
            'pdf' => 'application/octet-stream',
            'pem' => 'application/x-x509-user-cert',
            'pem' => 'application/x-pem-file',
            'pgp' => 'application/pgp',
            'php' => 'application/x-httpd-php',
            'php' => 'text/php',
            'php' => 'application/x-httpd-php-source',
            'png' => 'image/png',
            'ppt' => 'application/powerpoint',
            'doc' => 'application/msword',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'psd' => 'application/x-photoshop',
            'psd' => 'image/vnd.adobe.photoshop',
            'ra' => 'audio/x-realaudio',
            'ram' => 'audio/x-pn-realaudio',
            'rar' => 'application/rar',
            'rpm' => 'audio/x-pn-realaudio-plugin',
            'rsa' => 'application/x-pkcs7',
            'rtf' => 'text/rtf',
            'rtx' => 'text/richtext',
            'rv' => 'video/vnd.rn-realvideo',
            'sit' => 'application/x-stuffit',
            'smil' => 'application/smil',
            'srt' => 'text/srt',
            'svg' => 'image/svg+xml',
            'swf' => 'application/x-shockwave-flash',
            'tar' => 'application/x-tar',
            'tgz' => 'application/x-gzip-compressed',
            'tiff' => 'image/tiff',
            'ttf' => 'font/ttf',
            'txt' => 'text/plain',
            'vcf' => 'text/x-vcard',
            'vlc' => 'application/videolan',
            'vtt' => 'text/vtt',
            'wav' => 'audio/wav',
            'wbxml' => 'application/wbxml',
            'webm' => 'video/webm',
            'webp' => 'image/webp',
            'wma' => 'audio/x-ms-wma',
            'wmlc' => 'application/wmlc',
            'wmv' => 'video/x-ms-wmv',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'xhtml' => 'application/xhtml+xml',
            'xl' => 'application/excel',
            'xls' => 'application/xls',
            'xlsx' => 'application/vnd.ms-excel',
            'xml' => 'text/xml',
            'xsl' => 'text/xsl',
            'xspf' => 'application/xspf+xml',
            'z' => 'application/x-compress',
            'zip' => 'application/zip',
            'zsh' => 'text/x-scriptzsh'
        ];

        $explodedFileName = explode('.', $fileName);
        $extension = $explodedFileName[count($explodedFileName) - 1];
        $extension = explode('?', $extension)[0];
        return $map[$extension] ?? null;
    }

    /**
     * Returns the filename and extension (if set) from an URL
     *
     * @param string $url
     * @param boolean $withExtension
     * @return string
     */
    public static function getFilenameFromUrl(string $url, bool $withExtension = true): string
    {
        return $withExtension ? basename(parse_url($url, PHP_URL_PATH)) : pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
    }

    public static function getExtensionFromUrl(string $url): string
    {
        if (empty($url)) {
            throw new InvalidArgumentException("The URL can not be empty.");
        }
        $url = strtok($url, '?');
        $url = strtok($url, '#');
        $fileName = basename($url);
        if (empty($fileName)) {
            throw new RuntimeException("Can't extract filename from URL $url.");
        }
        return pathinfo($fileName, PATHINFO_EXTENSION);
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
     * Normalizes the string from São Paulo or São Paulo to sao paulo
     *
     * @param string $str
     * @return string
     */
    public static function normalizeString(string $str): string
    {
        return strtolower(trim(static::toLatin($str)));
    }

    /**
     * Returns the previous timespan as an array 'previousFrom', 'previousTo'. The new values are already in Carbon. If $simple is set to true, the plain difference in days will be returned, so a full month won't return the full month before but 31 days before!
     *
     * @param Carbon $from
     * @param Carbon $to
     * @param boolean $simple
     * @return array
     */
    public static function previousTimespan(Carbon $from, Carbon $to, bool $simple = false): array
    {
        if(!$simple && $from->day == 1 && $to->day == $to->endOfMonth()->day && $from->month == $to->month && $from->year == $to->year){
            //Should return the previous month
            return [
                'previousFrom' => $from->copy()->subMonth(),
                'previousTo' => $to->copy()->subMonth()
            ];
        }elseif(!$simple && $from->day == 1 && $from->month == 1 && $to->day == 31 && $to->month == 12 && $from->year == $to->year){
            //Should return the previous year
            return [
                'previousFrom' => $from->copy()->subYear(),
                'previousTo' => $to->copy()->subYear(),
            ];
        }
        else
        {
            //If simple is set to true, we ignore anything like full month or full year and just return the plain days
            $difference = $from->diffInDays($to);
            if($difference < 1){
                //If they are on the same day we can subtract only a single day
                return [
                    'previousFrom' => $from->copy()->subDay(),
                    'previousTo' => $from->copy()->subDay(),
                ];
            }
            $difference = round($difference); //Will round the difference to evade problems with 29.999999999 values which should be 30
            return [
                'previousFrom' => $from->copy()->subDays($difference),
                'previousTo' => $to->copy()->subDays($difference),
            ];
        }
    }

    /**
     * Checks if the date string is a valid date
     *
     * @param string $dateString Needs to be in format DD/MM
     * @return boolean
     */
    public static function isValidDate(string $dateString): bool
    {
        [$day, $month] = explode('/',$dateString);
        return checkdate((int)$month, (int)$day, 2000);
    }

    /**
     * Returns true if the startTime is after the endTime
     *
     * @param string $startTime Needs to be in HH:MM format
     * @param string $endTime Needs to be in HH:MM format
     * @return boolean
     */
    public static function isStartAfterEndTime(string $startTime, string $endTime): bool
    {
        [$startHours, $startMinutes] = explode(':', $startTime);
        [$endHours, $endMinutes] = explode(':', $endTime);
        $startTime = Carbon::createFromTime($startHours, $startMinutes);
        $endTime = Carbon::createFromTime($endHours, $endMinutes);
        return $startTime > $endTime;
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

    /**
     * Removes the tag from a string with or without endtag '\</tag>'
     *
     * @param string $string
     * @param string $tag
     * @param boolean $withEndTag
     * @return string
     */
    public static function stripTag(string $string, string $tag, bool $withEndTag = false): string
    {
        return $withEndTag ? preg_replace('/<\/?'.$tag.'\s*[^>]*>/i', '', $string) : preg_replace('/<'.$tag.'\s*[^>]*>/i', '', $string);
    }

    /**
     * Removes all tags from a string with or without endtag '\</tag>'
     *
     * @param string $string
     * @param string $tag
     * @param boolean $withEndTag
     * @return string
     */
    public static function stripTags(string $string, array $tags, bool $withEndTags = false): string
    {
        foreach($tags as $tag){
            if(!is_string($tag)){
                throw new Exception("Invalid type for tag ".gettype($tag));
            }
            $string = self::stripTag($string, $tag, $withEndTags);
        }
        return $string;
    }

    /**
     * Replaces a tag with a certain string
     *
     * @param string $string
     * @param string $tag
     * @param string $replacement
     * @return string
     */
    public static function replaceTag(string $string, string $tag, string $replacement):string
    {
        return preg_replace('/<'.$tag.'\s*[^>]*>/i', $replacement, $string);
    }

}
