<?php
declare(strict_types = 1);

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

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
     * @var ElasticSearchClient
     */
    private $elasticSearchClient;

    /**
     * SyncCompaniesSubsetHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ElasticSearchClient $elasticSearchClient
     * @param string $projectDir
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ElasticSearchClient $elasticSearchClient,
        string $projectDir
    ) {
        parent::__construct();
        $this->sourceRepository = $entityManager->getRepository(Source::class);
        $this->elasticSearchClient = $elasticSearchClient;
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
            $this->elasticSearchClient->index(
                [
                    'index' => 'data-gov-ro_companies',
                    'id' => $companyBuilder->getNationalUniqueIdentification(),
                    'body' => $companyBuilder->getData()
                ]
            );
            $progress->advance();
        }
        $progress->finish();;

        fclose($localFileHandle);
    }
}
