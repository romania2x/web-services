<?php

namespace App\Cache;

/**
 * Class OSMPropertiesCache
 * @package App\Cache
 */
class OSMPropertiesCache
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
     * @param $layerName
     * @param array $properties
     */
    public function set($layerName, array $properties)
    {
        $this->redisClient->set("osm.properties.$layerName", json_encode($properties));
    }

    /**
     * @param $layerName
     * @return array|null
     */
    public function get($layerName): ?array
    {
        if ($properties = $this->redisClient->get("osm.properties.$layerName")) {
            return json_decode($properties, true);
        }
        return null;
    }
}
