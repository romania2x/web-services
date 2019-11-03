<?php

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Entity\OpenData\Source;
use App\Helpers\LanguageHelpers;
use App\Message\DataGovRo\Companies\DownloadCompaniesSubset;
use App\MessageHandler\AbstractMessageHandler;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * Class DownloadCompaniesSubsetHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Companies
 */
class DownloadCompaniesSubsetHandler extends AbstractMessageHandler
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * DownloadCompaniesSubsetHandler constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->httpClient = new HttpClient([
            //todo: fake user-agents using faker
            RequestOptions::HEADERS => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36'
            ]
        ]);
    }


    /**
     * @param DownloadCompaniesSubset $message
     */
    public function __invoke(DownloadCompaniesSubset $message)
    {
        $localFile = $this->generateLocalFilePath($message->getSource());

        if (file_exists($localFile)) {
            $this->log("Cache hit {$message->getSource()->getUrl()}");

        } else {
            $this->log("Downloading {$message->getSource()->getUrl()}");
            $this->httpClient->get($message->getSource()->getUrl(), [RequestOptions::SINK => $localFile]);
        }

        $csvHandle = fopen($localFile, 'r');
        $defaultKeys = ['DENUMIRE', 'CUI', 'COD_INMATRICULARE', 'STARE_FIRMA', 'JUDET', 'LOCALITATE'];
        $keys = null;

        while ($row = $this->readWeirdFormat($csvHandle)) {

            if (!$keys && $row[0] == $defaultKeys[0]) {
                $keys = $row;
                continue;
            } elseif (!$keys) {
                $keys = array_slice($defaultKeys, 0, count($row));
            }
            $row = array_combine($keys, $row);
            print_r($row);
            break;
        }
        fclose($csvHandle);
    }

    /**
     * @param $csvHandle
     * @return array
     */
    private function readWeirdFormat($csvHandle): array
    {
        return array_map('App\Helpers\LanguageHelpers::safeLatinText', fgetcsv($csvHandle, 0, '|'));
    }

    /**
     * @param Source $source
     * @return string
     */
    private function generateLocalFilePath(Source $source)
    {
        //todo: figure out a better dir/file format
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'resources_' . md5($source->getUrl());
    }
}
