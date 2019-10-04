<?php

namespace App\Elasticsearch;

use Elasticsearch\ClientBuilder;

/**
 * Class ClientFactory
 * @package App\Elasticsearch
 */
class ClientFactory
{
    /**
     * @return \Elasticsearch\Client
     */
    public function __invoke()
    {
        return ClientBuilder::create()->setHosts(['internal-elasticsearch'])->build();
    }
}
