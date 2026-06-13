<?php

namespace LiveControls\Utils;

use Transliterator;

class Str
{
    /**
     * Converts to MD format from a html string.
     *
     * @param string $html
     * @return string
     */
    public static function convertHtmlToMD(string $html): string
    {
        $html = str_replace(["\r\n", "\r"], "\n", $html);
        $html = preg_replace('/<p[^>]*>/i', "\n\n", $html);
        $html = str_replace(['</p>'], "\n\n", $html);
        $html = str_replace(['<br>', '<br/>', '<br />'], "\n", $html);
        $html = preg_replace_callback('/<ul[^>]*>(.*?)<\/ul>/is', function ($m) {
            return preg_replace('/<li[^>]*>(.*?)<\/li>/is', "- $1\n", $m[1]);
        }, $html);
        $html = preg_replace_callback('/<ol[^>]*>(.*?)<\/ol>/is', function ($m) {
            $i = 1;
            return preg_replace_callback('/<li[^>]*>(.*?)<\/li>/is', function ($li) use (&$i) {
                return ($i++) . ". " . $li[1] . "\n";
            }, $m[1]);
        }, $html);
        $html = strip_tags($html);
        $html = preg_replace("/\n{3,}/", "\n\n", $html);
        return trim($html);
    }

    /**
     * Checks if the string is null or empty
     *
     * @param ?string $str
     * @return boolean
     */
    public static function isNullOrEmpty(?string $str): bool{
        return ($str === null || trim($str) === '');
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
     * @param array $tags
     * @param bool $withEndTags
     * @return string
     */
    public static function stripTags(string $string, array $tags, bool $withEndTags = false): string
    {
        foreach($tags as $tag){
            if(!is_string($tag)){
                throw new \Exception("Invalid type for tag ".gettype($tag));
            }
            $string = self::stripTag($string, $tag, $withEndTags);
        }
        return $string;
    }

    /**
     * Tries to convert a value into a string or returns null if it wasn't possible.
     *
     * @param mixed $value
     * @return string|null
     */
    public static function safeString(mixed $value): ?string
    {
        return is_scalar($value) ? (string)$value : null;
    }

    /**
     * Tries to fetch a string out of an array with its key and returns null if it can't be converted or if
     * the key does not exist.
     *
     * @param array $array
     * @param string $key
     * @return string|null
     */
    public static function safeStringFromArray(array $array, string $key): ?string
    {
        $value = $array[$key] ?? null;
        return self::safeString($value);
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
}