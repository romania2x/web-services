<?php

namespace App\Repository;

use GraphAware\Neo4j\OGM\EntityManagerInterface;
use GraphAware\Neo4j\Client\Client as Neo4jClient;
use JMS\Serializer\Serializer;

/**
 * Class AbstractRepository
 * @package App\Repository
 */
abstract class AbstractRepository
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var EntityManagerInterface
     */
    protected $graphEntityManager;

    /**
     * @var Neo4jClient
     */
    protected $neo4jClient;

    /**
     * AbstractOGMRepository constructor.
     * @param EntityManagerInterface $manager
     * @param Serializer    $serializer
     */
    public function __construct(EntityManagerInterface $manager, Serializer $serializer)
    {
        $this->graphEntityManager = $manager;
        $this->neo4jClient        = $manager->getDatabaseDriver();
        $this->serializer         = $serializer;
    }
}
