<?php

namespace App\Command\Companies;

use App\Command\AbstractCommand;
use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Companies\DownloadCompaniesSubset;
use App\Repository\Entity\OpenData\SourceRepository;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SyncCompaniesCommand
 * @package App\Command\Companies
 */
class SyncCompaniesCommand extends AbstractCommand
{
    protected static $defaultName = 'companies:sync';

    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * DownloadCompaniesCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface $messageBus
     */
    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        parent::__construct();
        $this->sourceRepository = $entityManager->getRepository(Source::class);
        $this->messageBus = $messageBus;
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sources = $this->sourceRepository->findDownloadableResourcesByTag(Source::TAG_COMPANIES_DATA_GOV_RO);

        foreach ($sources as $source) {
            $this->log("Syncing {$source->getUrl()}");
            $this->messageBus->dispatch(new DownloadCompaniesSubset($source));
        }
    }
}
