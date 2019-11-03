<?php

namespace App\Helpers;

/**
 * Class LanguageHelpers
 * @package App\Helpers
 */
abstract class LanguageHelpers
{
    public static function safeLatinText(string $text)
    {
        return iconv(mb_detect_encoding($text), 'ASCII//TRANSLIT', $text);
    }
}
