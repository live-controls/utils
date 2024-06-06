<?php

namespace LiveControls\Utils;

class Blogging
{
    /**
     * Calculates the estimated reading time of a text in minutes, based on the words per minute
     *
     * @param string $text
     * @param integer $wordsPerMinute
     * @return integer
     */
    public static function estimatedReadingTime(string $text, int $wordsPerMinute = 200): int
    {
        $wordsCount = str_word_count($text);
        return round($wordsCount / $wordsPerMinute);
    }

    /**
     * Replaces links like https://www.google.com with a html link
     *
     * @param string $text
     * @param bool $targetBlank
     * @param string $class
     * @param string $linkText
     * @return string
     */
    public static function linksToHtml(string $text, bool $targetBlank = false, string $class = "", string $linkText = ""): string
    {
        $url_regex = "/\b((https?:\/\/?|www\.)[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/)))/";

        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        
        preg_match_all($url_regex, $text, $urls);

        foreach ($urls[0] as $url) {
            if(Utils::isNullOrEmpty($linkText)){
                $linkText = $url;
            }
            $text = str_replace($url, "<a href='$url'".($targetBlank ? " target='_blank'" : '').(Utils::isNullOrEmpty($class) ? '' : " class='".$class."'").">$linkText</a>", $text);
        }

        return $text;
    }
}