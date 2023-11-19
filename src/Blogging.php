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
}