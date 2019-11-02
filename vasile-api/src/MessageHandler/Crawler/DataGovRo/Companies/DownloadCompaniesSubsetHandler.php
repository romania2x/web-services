<?php

namespace App\MessageHandler\Crawler\DataGovRo\Companies;


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
        $this->log("Downloading {$message->getSource()->getUrl()}");

        //save remote file to a temp file
        $localFile = tempnam(sys_get_temp_dir(), 'resource_');
        $this->httpClient->get($message->getSource()->getUrl(), [RequestOptions::SINK => $localFile]);

        $csvHandler = fopen($localFile, 'r');
        $keys = null;

        while ($row = fgetcsv($csvHandler, 0, '|')) {
            if (!$keys) {
                $keys = $row;
                continue;
            }
            $row = array_combine($keys, $row);
            print_r($row);
            break;
        }
        fclose($csvHandler);
        unlink($localFile);
    }

}
