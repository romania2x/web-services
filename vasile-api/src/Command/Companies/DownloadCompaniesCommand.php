<?php

namespace App\Command\Companies;

use App\Command\AbstractCommand;
use App\Entity\OpenData\Source;
use App\Repository\Entity\OpenData\SourceRepository;
use GraphAware\Neo4j\OGM\EntityManager;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DownloadCompaniesCommand
 * @package App\Command\Companies
 */
class DownloadCompaniesCommand extends AbstractCommand
{
    protected static $defaultName = 'companies:download';

    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * DownloadCompaniesCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->sourceRepository = $entityManager->getRepository(Source::class);
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
