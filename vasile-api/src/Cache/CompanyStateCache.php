<?php

namespace App\Cache;

/**
 * Class OSMPropertiesCache
 * @package App\Cache
 */
class CompanyStateCache
{
    /**
     * @var \Redis
     */
    private $redisClient;

    /**
     * OSMPropertiesCache constructor.
     * @param \Redis $redisClient
     */
    public function __construct(\Redis $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param int $state
     * @param string $description
     */
    public function set(int $state, string $description)
    {
        $this->redisClient->set("companies.state.$state", $description);
    }

    /**
     * @param $state
     * @return array|null
     */
    public function get($state): ?array
    {
        if ($description = $this->redisClient->get("companies.state.$state")) {
            return $description;
        }
        return null;
    }
}
