<?php

namespace App\Command\OpenData;

use App\Command\AbstractCommand;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PruneCommand
 * @package App\Command\DataGovRo
 */
class PruneCommand extends AbstractCommand
{
    protected static $defaultName = 'od:prune';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Redis
     */
    private $cache;

    /**
     * PruneCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param \Redis                 $cache
     */
    public function __construct(EntityManagerInterface $entityManager, \Redis $cache)
    {
        $this->entityManager = $entityManager;
        $this->cache         = $cache;
        parent::__construct();
    }


    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager->getDatabaseDriver()->run("match (s:Source)-[rel]-(b) delete rel");
        $this->entityManager->getDatabaseDriver()->run("match (s:Source) delete s");
    }
}
