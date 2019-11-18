<?php
declare(strict_types = 1);

namespace App\Helpers;

use Symfony\Component\Process\Process;

/**
 * Class LanguageHelpers
 * @package App\Helpers
 */
abstract class LanguageHelpers
{
    private static $locale;

    public static function mimeEncoding(string $filePath): string
    {
        $process = new Process("file -b --mime-encoding {$filePath}", getcwd());
        $process->run();
        return trim($process->getOutput());
    }

    /**
     * @param string $string
     * @return false|string
     */
    public static function asciiTranslit(string $string)
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    }

    /**
     * @param string $name
     * @return string
     */
    public static function normalizeName(string $name)
    {
        return mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * @param string $string
     * @return string
     */
    public static function slugify($text)
    {
        $text = self::asciiTranslit($text);

        // source: https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'N/A';
        }

        return $text;
    }
}
