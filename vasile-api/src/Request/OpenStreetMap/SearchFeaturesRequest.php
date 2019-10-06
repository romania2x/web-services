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
     * @param array|null $boundingBox
     * @return SearchFeaturesRequest
     */
    public function setBoundingBox(?array $boundingBox): SearchFeaturesRequest
    {
        $this->boundingBox = $boundingBox;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getLine(): ?array
    {
        return $this->line;
    }

    /**
     * @param array|null $line
     * @return SearchFeaturesRequest
     */
    public function setLine(?array $line): SearchFeaturesRequest
    {
        $this->line = $line;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPolygon(): ?array
    {
        return $this->polygon;
    }

    /**
     * @param array|null $polygon
     * @return SearchFeaturesRequest
     */
    public function setPolygon(?array $polygon): SearchFeaturesRequest
    {
        $this->polygon = $polygon;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getRoad(): ?array
    {
        return $this->road;
    }

    /**
     * @param array|null $road
     * @return SearchFeaturesRequest
     */
    public function setRoad(?array $road): SearchFeaturesRequest
    {
        $this->road = $road;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPoint(): ?array
    {
        return $this->point;
    }

    /**
     * @param array|null $point
     * @return SearchFeaturesRequest
     */
    public function setPoint(?array $point): SearchFeaturesRequest
    {
        $this->point = $point;
        return $this;
    }


}
