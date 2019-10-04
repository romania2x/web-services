<?php

namespace App\Request\OpenStreetMap;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class SearchFeaturesRequest
 * @package App\Request\OpenStreetMap
 */
class SearchFeaturesRequest
{
    /**
     * @var array|null
     */
    private $boundingBox;

    /**
     * @var array|null
     * @Serializer\Type(name="array")
     */
    private $line;

    /**
     * @var array|null
     * @Serializer\Type(name="array")
     */
    private $polygon;

    /**
     * @var array|null
     * @Serializer\Type(name="array")
     */
    private $road;

    /**
     * @var array|null
     * @Serializer\Type(name="array")
     */
    private $point;

    /**
     * @return array|null
     */
    public function getBoundingBox(): ?array
    {
        return $this->boundingBox;
    }

    /**
     * @return array
     */
    public function getLine(): ?array
    {
        return $this->line;
    }

    /**
     * @return array
     */
    public function getPolygon(): ?array
    {
        return $this->polygon;
    }

    /**
     * @return array
     */
    public function getRoad(): ?array
    {
        return $this->road;
    }

    /**
     * @return array
     */
    public function getPoint(): ?array
    {
        return $this->point;
    }
}
