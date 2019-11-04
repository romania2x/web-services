<?php

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Entity\OpenData\Source;
use Symfony\Component\Process\Process;

trait DataGovRoCompaniesParserTrait
{
    /**
     * @var string
     */
    protected $downloadCacheDir;

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
        $row = trim(fgets($csvHandle));

        switch ($this->encoding) {
            case 'ISO-8859-2':
                $row = mb_convert_encoding($row, 'UTF-8', $this->encoding);
                break;
        }

        if ($row == '') {
            return null;
        }

        $row = explode($this->separator, $row);

        setlocale(LC_ALL, 'ro_RO.utf8');
        $row = array_map(function ($string) {
            return iconv($this->encoding, 'ASCII//TRANSLIT', $string);
        }, $row);

        if (count($this->keys) == 0) {
            return $row;
        }

        return array_combine($this->keys, $row);
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
     * @param resource $fileHandle
     * @param string $filePath
     */
    protected function autoDetectConfiguration($fileHandle, string $filePath)
    {
        $this->keys = [];
        $this->separator = null;
        $this->encoding = null;

        $details = $this->getSourceFileInfo($filePath);

        $this->encoding = $details['encoding'];

        $row = fgets($fileHandle);
        rewind($fileHandle);

        if (strpos($row, '|') > 0) {
            $this->separator = '|';
        } elseif (strpos($row, '^') > 0) {
            $this->separator = '^';
        } else {
            $this->separator = ',';
        }

        $this->keys = $this->readWeirdFormat($fileHandle);

        if (!isset($this->keys[0])) {
            var_dump($this->keys);
            die;
        }

        if (!in_array($this->keys[0], ['DENUMIRE', 'COD'])) {
            if (count($this->keys) == 2) {
                $this->keys = ['COD', 'DENUMIRE'];
            } elseif (count($this->keys) > 4) {
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

    /**
     * @param string $localFilePath
     * @return array
     */
    protected function getSourceFileInfo(string $localFilePath)
    {
        $process = new Process("file -b --mime-encoding {$localFilePath}", getcwd());
        $process->run();
        $encoding = trim($process->getOutput());


        $process = new Process("file -b -z {$localFilePath}", getcwd());
        $process->run();
        $description = trim($process->getOutput());

        return [
            'encoding' => $encoding,
            'description' => $description
        ];
    }
}
