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
        //todo: romanian documents -> ISO-8859-2
        //todo: default -> UTF-8
//        setlocale(LC_CTYPE, 'ro_RO');
//        $text = mb_convert_encoding($text, 'UTF-8', 'ISO-8859-2');
        $encoding = mb_detect_encoding($text);
        switch (mb_detect_encoding($text)) {
            case 'ASCII':
                $encoding = 'ISO-8859-2';
                break;
            case 'UTF-8':
                break;
        }
        var_dump($encoding);
        return iconv($encoding, 'ASCII//TRANSLIT', $text);
    }
}
