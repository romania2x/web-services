<?php
declare(strict_types = 1);

namespace App\MessageHandler\Crawler\DataGovRo;

use App\Message\DataGovRo\DataSetDownload;
use App\Message\DataGovRo\DataSetDetailsUpdate;
use App\Message\DataGovRo\DataSetProcess;
use App\MessageHandler\AbstractMessageHandler;
use App\Repository\Entity\OpenData\SourceRepository;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use GuzzleHttp\RequestOptions;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;
use GuzzleHttp\Client as HttpClient;

/**
 * Class DataSetDetailsUpdateHandler
 * @package App\MessageHandler\Crawler\DataGovRo
 */
class DataSetDetailsUpdateHandler extends AbstractMessageHandler
{
    /**
     * @var Client
     */
    private $crawlerClient;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * DataSetDetailsUpdateHandler constructor.
     * @param SourceRepository $sourceRepository
     */
    public function __construct(SourceRepository $sourceRepository)
    {
        parent::__construct();
        $this->crawlerClient    = Client::createChromeClient();
        $this->sourceRepository = $sourceRepository;
        $this->httpClient       = new HttpClient(
            [
                RequestOptions::HEADERS => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36'
                ]
            ]
        );
    }


    /**
     * @param DataSetDetailsUpdate $message
     * @throws \Exception
     */
    public function __invoke(DataSetDetailsUpdate $message)
    {
        $this->log("Downloading details for {$message->getUrl()}");

        $crawler = new Crawler($this->httpClient->get($message->getUrl())->getBody()->getContents());

        $set = [
            'title'       => trim($crawler->filter('.module-content h1')->text()),
            'description' => trim($crawler->filter('.module-content .notes')->text()),
            'resources'   => []
        ];

        $dataSetSource = $this->sourceRepository->createOrUpdate(
            $message->getUrl(),
            $message->getType(),
            $set['title'],
            $set['description']
        );

        /** @var \DOMElement $resourceItem */
        foreach ($crawler->filter('.resource-list .resource-item') as $index => $resourceItem) {
            $child  = $index + 1;
            $parent = ".resource-list .resource-item:nth-child({$child})";

            $resource = $this->sourceRepository->createOrUpdate(
                trim($crawler->filter($parent . " ul.dropdown-menu > li:nth-child(2) > a")->first()->attr('href')),
                $message->getType(),
                trim($crawler->filter($parent . ' .heading')->first()->attr('title')),
                null,
                true,
                $dataSetSource
            );

            $this->messageBus->dispatch(new DataSetDownload($resource));
        }

        $this->messageBus->dispatch(new DataSetProcess($dataSetSource));
    }
}
