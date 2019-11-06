<?php
declare(strict_types = 1);

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Elasticsearch\Indexer;
use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Companies\SyncCompaniesSubset;
use App\MessageHandler\AbstractMessageHandler;
use App\ModelCompositeBuilder\DataGovRo\CompanyBuilder;
use App\Repository\Entity\OpenData\SourceRepository;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use Elasticsearch\Client as ElasticSearchClient;

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
     * @var Indexer
     */
    private $indexer;

    /**
     * SyncCompaniesSubsetHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param Indexer $indexer
     * @param string $projectDir
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Indexer $indexer,
        string $projectDir
    ) {
        parent::__construct();
        $this->sourceRepository = $entityManager->getRepository(Source::class);
        $this->indexer = $indexer;
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

        $date = strtotime($this->detectDateFromSource($source));

        $this->log("Processing {$source->getUrl()} ({$localFile} <-> {$this->encoding})");

        $localFileHandle = fopen($localFile, 'r');

        $this->autoDetectConfiguration($localFileHandle, $localFile);

        $progress = new ProgressBar($this->output);
        $progress->start();

        while ($row = $this->readWeirdFormat($localFileHandle)) {
            if (count($row) == 0) {
                continue;
            }
            $companyBuilder = new CompanyBuilder();
            foreach ($row as $key => $value) {
                $companyBuilder->addData($key, $value, $date);
            }

            $this->indexer->indexCompany($companyBuilder->getCompany(), $source);
            $progress->advance();
        }
        $progress->finish();

        fclose($localFileHandle);
    }
}
