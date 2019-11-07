<?php

namespace App\MessageHandler\Crawler\DataGovRo;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\DataSetDownload;
use App\MessageHandler\AbstractMessageHandler;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * Class DataSetDownloadHandler
 * @package App\MessageHandler\Crawler\DataGovRo
 */
class DataSetDownloadHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;
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
     * @param DataSetDownload $message
     */
    public function __invoke(DataSetDownload $message)
    {
        $localFile = $this->generateLocalFilePath($message->getSource());

        if (!file_exists($localFile)) {
            $this->log("Downloading {$message->getSource()->getUrl()}");
            $this->httpClient->get($message->getSource()->getUrl(), [RequestOptions::SINK => $localFile]);
        } else {
            $this->log("Cache hit {$localFile} => {$message->getSource()->getUrl()}");
        }
    }

}
