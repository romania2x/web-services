<?php

namespace App\Entity\Administrative;

use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 * Class Zone
 * @package App\Entity\Administrative
 * @OGM\Node(label="Zone", repository="App\Repository\Entity\Administrative\ZoneRepository")
 */
class Zone
{
    /**
     * @var int
     * @OGM\GraphId()
     */
    private $id;

    /**
     * @var int
     * @OGM\Property(type="int")
     */
    private $nationalId;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    private $name;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    private $slug;

    /**
     * @var int
     * @OGM\Property(type="int")
     */
    private $siruta;

    /**
     * @var Collection|County[]
     * @OGM\Relationship(type="PARENT", direction="OUTGOING", collection=true, mappedBy="zone", targetEntity="County")
     */
    private $counties;

    /**
     * Zone constructor.
     */
    public function __construct()
    {
        $this->counties = new Collection();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Zone
     */
    public function setId(int $id): Zone
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getNationalId(): int
    {
        return $this->nationalId;
    }

    /**
     * @param int $nationalId
     * @return Zone
     */
    public function setNationalId(int $nationalId): Zone
    {
        $this->nationalId = $nationalId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Zone
     */
    public function setName(string $name): Zone
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Zone
     */
    public function setSlug(string $slug): Zone
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return int
     */
    public function getSiruta(): int
    {
        return $this->siruta;
    }

    /**
     * @param int $siruta
     * @return Zone
     */
    public function setSiruta(int $siruta): Zone
    {
        $this->siruta = $siruta;
        return $this;
    }

    /**
     * @return County[]|Collection
     */
    public function getCounties()
    {
        return $this->counties;
    }
}
