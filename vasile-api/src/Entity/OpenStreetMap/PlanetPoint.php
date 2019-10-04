<?php

namespace App\Entity\OpenStreetMap;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PlanetPoint
 * @package App\Entity\OpenStreetMap
 * @ORM\Entity(repositoryClass="App\Repository\Entity\OpenStreetMap\PlanetPointRepository")
 * @ORM\Table(name="planet_osm_point")
 */
class PlanetPoint implements OpenStreetMapEntityInterface
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     */
    public $osmId;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $access;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $adminLevel;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $aerialway;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $aeroway;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $amenity;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $area;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $barrier;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $bicycle;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $brand;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $bridge;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $boundary;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $building;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $capital;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $construction;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $covered;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $culvert;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $cutting;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $denomination;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $disused;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $ele;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $embankment;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $foot;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $harbour;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $highway;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $historic;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $horse;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $intermittent;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $junction;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $landuse;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $layer;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $leisure;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $lock;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $manMade;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $military;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $motorcar;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $name;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $natural;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $office;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $oneway;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $operator;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $place;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $population;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $power;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $powerSource;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $publicTransport;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $railway;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $ref;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $religion;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $route;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $service;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $shop;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $sport;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $surface;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $toll;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $tourism;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $tunnel;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $water;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $waterway;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $wetland;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $width;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $wood;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    public $zOrder;
    /**
     * @var string
     * @ORM\Column(type="geometry")
     */
    public $way;

    /**
     * @return string
     */
    public function getWay(): string
    {
        return $this->way;
    }

    /**
     * @param $way
     */
    public function setWay($way)
    {
        $this->way = $way;
    }
    
    /**
     * @return int
     */
    public function getOsmId(): int
    {
        return $this->osmId;
    }
}
