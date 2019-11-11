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
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\Client;

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
    }


    /**
     * @param DataSetDetailsUpdate $message
     * @throws NoSuchElementException
     * @throws TimeOutException
     * @throws \Exception
     */
    public function __invoke(DataSetDetailsUpdate $message)
    {
        $this->log("Downloading details for {$message->getUrl()}");

        $this->crawlerClient->request('GET', $message->getUrl());
        $crawler = $this->crawlerClient->waitFor('.module-content');

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

        /** @var RemoteWebElement $resourceItem */
        foreach ($crawler->filter('.resource-list .resource-item') as $resourceItem) {
            $resource = $this->sourceRepository->createOrUpdate(
                trim($resourceItem->findElement(WebDriverBy::cssSelector('ul.dropdown-menu > li:nth-child(2) > a'))->getAttribute('href')),
                $message->getType(),
                trim($resourceItem->findElement(WebDriverBy::cssSelector('.heading'))->getAttribute('title')),
                null,
                true,
                $dataSetSource
            );

            $this->messageBus->dispatch(new DataSetDownload($resource));
        }

        $this->messageBus->dispatch(new DataSetProcess($dataSetSource));
    }
}
