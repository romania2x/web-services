<?php

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Entity\OpenData\Source;
use App\Helpers\LanguageHelpers;
use App\Message\DataGovRo\Companies\DownloadCompaniesSubset;
use App\Message\DataGovRo\Companies\PreloadStateMap;
use App\Message\DataGovRo\Companies\SyncCompaniesSubset;
use App\MessageHandler\AbstractMessageHandler;
use App\Repository\Entity\OpenData\SourceRepository;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Messenger\MessageBusInterface;

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
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * @var string
     */
    private $downloadCacheDir;

    /**
     * DownloadCompaniesSubsetHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param string $projectDir
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        string $projectDir
    ) {
        parent::__construct();
        $this->httpClient = new HttpClient([
            //todo: fake user-agents using faker
            RequestOptions::HEADERS => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36'
            ]
        ]);
        $this->sourceRepository = $entityManager->getRepository(Source::class);
        $this->downloadCacheDir = "$projectDir/download_cache/";
    }


    /**
     * @param DownloadCompaniesSubset $message
     */
    public function __invoke(DownloadCompaniesSubset $message)
    {
        /** @var Source $source */
        $source = $this->sourceRepository->find($message->getSourceId());
        $localFile = $this->generateLocalFilePath($source);

        if (!file_exists($localFile)) {
            $this->log("Downloading {$source->getUrl()}");
            $this->httpClient->get($source->getUrl(), [RequestOptions::SINK => $localFile]);
        }

        if (strpos($source->getUrl(), 'nomenclator') !== false) {
            $this->messageBus->dispatch(new PreloadStateMap($source));
        } else {
            $this->messageBus->dispatch(new SyncCompaniesSubset($source));
        }
    }

    /**
     * @param Source $source
     * @return string
     */
    private function generateLocalFilePath(Source $source)
    {
        return $this->downloadCacheDir . 'resources_' . md5($source->getUrl());
    }
}
