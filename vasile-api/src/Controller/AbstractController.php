<?php

namespace App\Controller;

use App\Mercure\MercureClient;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\Client as ElasticSearchClient;
use GraphAware\Neo4j\OGM\EntityManagerInterface as GraphEntityManager;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController extends BaseAbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var GraphEntityManager
     */
    private $graphEntityManager;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var MercureClient
     */
    private $mercureClient;

    /**
     * @var ElasticSearchClient
     */
    private $elasticSearchClient;

    /**
     * DefaultController constructor.
     * @param DocumentManager $documentManager
     * @param GraphEntityManager $graphEntityManager
     * @param MessageBusInterface $messageBus
     * @param EntityManagerInterface $entityManager
     * @param MercureClient $mercureClient
     * @param ElasticSearchClient $elasticSearchClient
     */
    public function __construct(
        DocumentManager $documentManager,
        GraphEntityManager $graphEntityManager,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        MercureClient $mercureClient,
        ElasticSearchClient $elasticSearchClient
    ) {
        $this->documentManager = $documentManager;
        $this->graphEntityManager = $graphEntityManager;
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
        $this->mercureClient = $mercureClient;
        $this->elasticSearchClient = $elasticSearchClient;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager(): DocumentManager
    {
        return $this->documentManager;
    }

    /**
     * @return GraphEntityManager
     */
    public function getGraphEntityManager(): GraphEntityManager
    {
        return $this->graphEntityManager;
    }

    /**
     * @return MessageBusInterface
     */
    public function getMessageBus(): MessageBusInterface
    {
        return $this->messageBus;
    }

    /**
     * @return MercureClient
     */
    public function getMercureClient(): MercureClient
    {
        return $this->mercureClient;
    }

    /**
     * @return ElasticSearchClient
     */
    public function getElasticSearchClient(): ElasticSearchClient
    {
        return $this->elasticSearchClient;
    }

    /**
     * @return \Redis
     */
    public function getCache(): \Redis
    {
        return $this->container->get('cache');
    }
}
