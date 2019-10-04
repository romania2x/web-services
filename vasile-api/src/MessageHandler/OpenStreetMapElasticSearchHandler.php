<?php

namespace App\MessageHandler;

use App\Message\IndexOpenStreetMapEntityMessage;
use App\Transformer\ElasticSearch\OSMEntityTransformer;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Elasticsearch\Client as ElasticSearchClient;

/**
 * Class OpenStreetMapElasticSearchHandler
 * @package App\MessageHandler
 */
class OpenStreetMapElasticSearchHandler implements MessageHandlerInterface
{
    /**
     * @var OSMEntityTransformer
     */
    private $transformer;

    /**
     * @var ElasticSearchClient
     */
    private $elasticSearchClient;

    /**
     * OpenStreetMapElasticSearchHandler constructor.
     * @param OSMEntityTransformer $transformer
     * @param ElasticSearchClient $elasticSearchClient
     */
    public function __construct(OSMEntityTransformer $transformer, ElasticSearchClient $elasticSearchClient)
    {
        $this->transformer = $transformer;
        $this->elasticSearchClient = $elasticSearchClient;
    }

    public function __invoke(IndexOpenStreetMapEntityMessage $indexOpenStreetMapEntityMessage)
    {
        $this->elasticSearchClient->index($this->transformer->transform($indexOpenStreetMapEntityMessage->getEntity()));
        echo '.';
    }

}
