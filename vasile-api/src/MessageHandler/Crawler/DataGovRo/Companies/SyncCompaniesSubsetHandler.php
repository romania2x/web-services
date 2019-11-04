<?php
declare(strict_types = 1);

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Companies\SyncCompaniesSubset;
use App\MessageHandler\AbstractMessageHandler;
use App\Repository\Entity\OpenData\SourceRepository;
use GraphAware\Neo4j\OGM\EntityManagerInterface;

/**
 * Class SyncCompaniesSubsetHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Companies
 */
class SyncCompaniesSubsetHandler extends AbstractMessageHandler
{
    use DataGovRoCompaniesParserTrait;
    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * SyncCompaniesSubsetHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param string $projectDir
     */
    public function __construct(EntityManagerInterface $entityManager, string $projectDir)
    {
        parent::__construct();
        $this->sourceRepository = $entityManager->getRepository(Source::class);
        $this->downloadCacheDir = "$projectDir/download_cache/";
    }

    /**
     * @param SyncCompaniesSubset $message
     */
    public function __invoke(SyncCompaniesSubset $message)
    {
        /** @var Source $source */
        $source = $this->sourceRepository->find($message->getSourceId());
        $localFile = $this->generateLocalFilePath($source);

        $date = $this->detectDateFromSource($source);

        $this->log("Processing {$source->getUrl()} ({$localFile} <-> {$this->encoding})");

        $localFileHandle = fopen($localFile, 'r');

        $this->autoDetectConfiguration($localFileHandle, $localFile);

        while ($row = $this->readWeirdFormat($localFileHandle)) {
            print_r($row);
            break;
        }

        //reset
        fclose($localFileHandle);
    }
}
