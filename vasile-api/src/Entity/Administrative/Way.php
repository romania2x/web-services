<?php

declare(strict_types = 1);

namespace App\Entity\Administrative;

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * Class Way
 * @package App\Entity\Administrative
 * @OGM\Node(label="Way")
 */
class Way
{
    /**
     * @var int
     * @OGM\GraphId()
     */
    private $id;
    /**
     * @var string
     * @OGM\Property(type="string")
     */
    private $title;
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
    private $countyId;

    /**
     * @var AdministrativeUnit
     * @OGM\Relationship(type="PARENT", direction="INCOMING", collection=false,
     *                                  targetEntity="AdministrativeUnit")
     */
    private $administrativeUnit;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Way
     */
    public function setId(int $id): Way
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Way
     */
    public function setTitle(string $title): Way
    {
        $this->title = $title;
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
     * @return Way
     */
    public function setName(string $name): Way
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
     * @return Way
     */
    public function setSlug(string $slug): Way
    {
        $this->slug = $slug;
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
     * @return Way
     */
    public function setAdministrativeUnit(AdministrativeUnit $administrativeUnit): Way
    {
        $this->administrativeUnit = $administrativeUnit;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountyId(): int
    {
        return $this->countyId;
    }

    /**
     * @param int $countyId
     * @return Way
     */
    public function setCountyId(int $countyId): Way
    {
        $this->countyId = $countyId;
        return $this;
    }
}
