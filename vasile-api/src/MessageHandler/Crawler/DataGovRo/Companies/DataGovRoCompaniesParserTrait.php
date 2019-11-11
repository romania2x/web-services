<?php

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Entity\OpenData\Source;
use App\Helpers\LanguageHelpers;
use Symfony\Component\Process\Process;

trait DataGovRoCompaniesParserTrait
{
    protected $locale = 'ro_RO.utf8';


    /**
     * @var string
     */
    protected $separator;

    /**
     * @var string
     */
    protected $encoding;

    /**
     * @var array
     */
    protected $keys = [];

    /**
     * @param $csvHandle
     * @return array|null
     */
    protected function readWeirdFormat($csvHandle): ?array
    {
        $row = fgets($csvHandle);
        if ($row === false) {
            return null;
        }
        $row = trim(LanguageHelpers::asciiTranslit($row, $this->encoding));

        if ($row == '') {
            return [];
        }

        $row = array_map(function ($value) {
            return trim(str_replace('"', '', $value));
        }, explode($this->separator, $row));

        if (count($this->keys) == 0) {
            return $row;
        }

        if (count($this->keys) != count($row)) {
            if (count($row) == 1) {
                return [];
            } elseif (count($this->keys) < count($row)) {
                $row = array_slice($row, 0, count($this->keys));
            } else {
                var_dump($this->keys, $row);
                die;
            }
        }

        return array_combine($this->keys, $row);
    }

    /**
     * @param resource $fileHandle
     * @param string $filePath
     */
    protected function autoDetectConfiguration($fileHandle, string $filePath)
    {
        $this->keys = [];
        $this->separator = null;
        $this->encoding = LanguageHelpers::mimeEncoding($filePath);

        $row = LanguageHelpers::asciiTranslit(fgets($fileHandle), $this->encoding);
        rewind($fileHandle);

        if (strpos($row, '|') > 0) {
            $this->separator = '|';
        } elseif (strpos($row, '^') > 0) {
            $this->separator = '^';
        } else {
            $this->separator = ',';
        }

        $this->keys = $this->readWeirdFormat($fileHandle);

        if (!in_array($this->keys[0], ['DENUMIRE', 'COD'])) {
            if (count($this->keys) == 2) {
                $this->keys = ['COD', 'DENUMIRE'];
            } elseif (count($this->keys) > 2) {
                $this->keys = array_slice([
                    'DENUMIRE',
                    'CUI',
                    'COD_INMATRICULARE',
                    'STARE_FIRMA',
                    'JUDET',
                    'LOCALITATE'
                ], 0, count($this->keys));
            }
            rewind($fileHandle);
        }
    }

    /**
     * @param Source $source
     * @return string
     */
    protected function detectDateFromSource(Source $source): ?string
    {
        preg_match_all('/\d{2}[.]\d{2}[.]\d{4}/', $source->getUrl(), $matches);
        if (isset($matches[0][0])) {
            return $matches[0][0];
        }
        preg_match_all('/\d{4}[-]\d{2}[-]\d{2}/', $source->getUrl(), $matches);
        if (isset($matches[0][0])) {
            return $matches[0][0];
        }
        return null;
    }
}
