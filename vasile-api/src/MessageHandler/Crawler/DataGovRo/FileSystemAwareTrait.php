<?php

namespace App\MessageHandler\Crawler\DataGovRo;

use App\Entity\OpenData\Source;
use App\Helpers\LanguageHelpers;

trait FileSystemAwareTrait
{
    /**
     * @var string
     */
    protected $downloadCacheDir;

    /**
     * @param string $location
     */
    public function setProjectDir(string $location)
    {
        $this->downloadCacheDir = "$location/download_cache/";
    }

    /**
     * @param Source $source
     * @return string
     */
    protected function generateLocalFilePath(Source $source)
    {
        return $this->downloadCacheDir . 'resources_' . md5($source->getUrl());
    }

    /**
     * @param Source      $source
     * @param null        $rowCallback
     * @param string      $separator
     * @param string|null $encoding
     */
    protected function processCSVFromSource(
        Source $source,
        $rowCallback = null,
        $separator = ',',
        ?string $encoding = 'UTF-8'
    ) {
        $this->processCSVFromPath($this->generateLocalFilePath($source), $rowCallback, $separator, $encoding);
    }

    /**
     * @param string        $path
     * @param callable|null $rowCallback
     * @param string|null   $separator
     * @param string|null   $encoding
     */
    protected function processCSVFromPath(
        string $path,
        $rowCallback = null,
        ?string $separator = ',',
        ?string $encoding = 'UTF-8'
    ) {
        $fileHandle = fopen($path, 'r');
        $keys       = [];
        while ($row = fgets($fileHandle)) {
            $row = str_getcsv($row, $separator);
            if (count($keys) == 0) {
                $keys = $row;
                continue;
            }
            $row = array_map(function ($value) use ($encoding) {
                return trim(LanguageHelpers::asciiTranslit($value, $encoding));
            }, $row);
            if (count($keys) != count($row)) {
                print_r($keys);
                print_r($row);
            }
            $row = array_combine($keys, $row);
            if ($rowCallback) {
                $rowCallback($row);
            }
        }
        fclose($fileHandle);
    }

    /**
     * @param Source $source
     * @return int
     */
    protected function getNoLinesFromSource(Source $source): int
    {
        return $this->getNoLinesFromPath($this->generateLocalFilePath($source));
    }

    /**
     * @param string $path
     * @return int
     */
    protected function getNoLinesFromPath(string $path): int
    {
        $count  = 0;
        $handle = fopen($path, "r");
        while (!feof($handle)) {
            fgets($handle);
            $count++;
        }

        fclose($handle);

        return $count;
    }
}
