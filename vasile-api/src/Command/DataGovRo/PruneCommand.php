<?php

namespace App\Command\DataGovRo;

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
    protected static $defaultName = 'data-gov-ro:prune';

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
        $this->entityManager->getDatabaseDriver()->run("match (w:Way)-[rel]-(b) delete rel");
        $this->entityManager->getDatabaseDriver()->run("match (w:Way) delete w");
        $this->entityManager->getDatabaseDriver()->run("match (a:AdministrativeUnit)-[rel]-(b) delete rel");
        $this->entityManager->getDatabaseDriver()->run("match (a:AdministrativeUnit) delete  a");
        $this->entityManager->getDatabaseDriver()->run("match (c:County)-[rel]-(b) delete rel");
        $this->entityManager->getDatabaseDriver()->run("match (c:County) delete c");
        $this->entityManager->getDatabaseDriver()->run("match (z:Zone)-[rel]-(b) delete rel");
        $this->entityManager->getDatabaseDriver()->run("match (z:Zone) delete z");
        $this->cache->del($this->cache->keys('administrative_unit.*'));
    }


}
