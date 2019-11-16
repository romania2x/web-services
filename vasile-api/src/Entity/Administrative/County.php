<?php

namespace App\Entity\Administrative;

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * Class County
 * @package App\Entity\Administrative
 * @OGM\Node(label="County", repository="App\Repository\Entity\Administrative\CountyRepository")
 */
class County
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
     * @var string
     * @OGM\Property(type="string")
     */
    private $mnemonic;

    /**
     * @var AdministrativeUnit
     * @OGM\Relationship(type="PARENT", direction="OUTGOING", collection=false, targetEntity="AdministrativeUnit")
     */
    private $administrativeUnit;

    /**
     * @var Zone
     * @OGM\Relationship(type="PARENT", direction="INCOMING", collection=false, mappedBy="counties",
     *                                  targetEntity="Zone")
     */
    private $zone;

    /**
     * @var int
     * @OGM\Property(type="int")
     */
    private $sortingIndex;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return County
     */
    public function setId(int $id): County
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
     * @return County
     */
    public function setNationalId(int $nationalId): County
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
     * @return County
     */
    public function setName(string $name): County
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
     * @return County
     */
    public function setSlug(string $slug): County
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getMnemonic(): string
    {
        return $this->mnemonic;
    }

    /**
     * @param string $mnemonic
     * @return County
     */
    public function setMnemonic(string $mnemonic): County
    {
        $this->mnemonic = $mnemonic;
        return $this;
    }

    /**
     * @return Zone
     */
    public function getZone(): Zone
    {
        return $this->zone;
    }

    /**
     * @param Zone $zone
     * @return County
     */
    public function setZone(Zone $zone): County
    {
        $this->zone = $zone;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortingIndex(): int
    {
        return $this->sortingIndex;
    }

    /**
     * @param int $sortingIndex
     * @return County
     */
    public function setSortingIndex(int $sortingIndex): County
    {
        $this->sortingIndex = $sortingIndex;
        return $this;
    }

    /**
     * @return AdministrativeUnit
     */
    public function getAdministrativeUnit(): AdministrativeUnit
    {
        return $this->administrativeUnit;
    }

    /**
     * @param AdministrativeUnit $administrativeUnit
     * @return County
     */
    public function setAdministrativeUnit(AdministrativeUnit $administrativeUnit): County
    {
        $this->administrativeUnit = $administrativeUnit;
        return $this;
    }
}
