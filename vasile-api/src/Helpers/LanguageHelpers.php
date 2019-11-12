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
     * @param string $sourceEncoding
     * @param string $locale
     * @return false|string
     */
    public static function asciiTranslit(string $string, string $sourceEncoding, string $locale = 'ro_RO.utf8')
    {
        if ($locale != self::$locale) {
            self::$locale = $locale;
            setlocale(LC_ALL, self::$locale);
        }
        mb_convert_encoding($string,'UTF-8',$sourceEncoding);
        $string = self::convertSmartQuotes($string);
        return iconv($sourceEncoding, 'ASCII//TRANSLIT', $string);
    }

    public static function convertSmartQuotes(string $string): string
    {
        $search = array(
            chr(145),
            chr(146),
            chr(147),
            chr(148),
            chr(151)
        );

        $replace = array(
            "'",
            "'",
            '"',
            '"',
            '-'
        );

        return str_replace($search, $replace, $string);
    }
}
