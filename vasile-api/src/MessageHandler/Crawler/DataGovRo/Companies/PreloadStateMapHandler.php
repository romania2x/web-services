<?php
declare(strict_types = 1);

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Cache\CompanyStateCache;
use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Companies\PreloadStateMap;
use App\MessageHandler\AbstractMessageHandler;
use App\Repository\Entity\OpenData\SourceRepository;
use GraphAware\Neo4j\OGM\EntityManagerInterface;

/**
 * Class PreloadStateMapHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Companies
 */
class PreloadStateMapHandler extends AbstractMessageHandler
{
    use DataGovRoCompaniesParserTrait;

    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * @var CompanyStateCache
     */
    private $companyStateCache;

    /**
     * SyncCompaniesSubsetHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param CompanyStateCache $companyStateCache
     * @param string $projectDir
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CompanyStateCache $companyStateCache,
        string $projectDir
    ) {
        parent::__construct();
        $this->sourceRepository = $entityManager->getRepository(Source::class);
        $this->companyStateCache = $companyStateCache;
        $this->downloadCacheDir = "$projectDir/download_cache/";
    }

    /**
     * @param PreloadStateMap $message
     */
    public function __invoke(PreloadStateMap $message)
    {
        /** @var Source $source */
        $source = $this->sourceRepository->find($message->getSourceId());
        $localFile = $this->generateLocalFilePath($source);

        $this->log("Preloading states from {$source->getUrl()} ({$localFile})");

        $localFileHandle = fopen($localFile, 'r');

        $this->autoDetectConfiguration($localFileHandle, $localFile);

        while ($row = $this->readWeirdFormat($localFileHandle)) {
            $this->companyStateCache->set(intval($row['COD']), $row['DENUMIRE']);
        }

        //reset
        fclose($localFileHandle);
    }
}
