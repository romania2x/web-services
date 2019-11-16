<?php

declare(strict_types = 1);

namespace App\Entity\Administrative;

use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 * Class AdministrativeUnit
 * @package App\Entity\Administrative
 * @OGM\Node(label="AdministrativeUnit")
 */
class AdministrativeUnit
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
    private $name;

    /**
     * @var string|null
     * @OGM\Property(type="string")
     */
    private $title;

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
     * @var array
     * @OGM\Property(type="array")
     */
    private $postalCodes;


    /**
     * @var int|null
     * @OGM\Property(type="int")
     */
    private $type;

    /**
     * @var AdministrativeUnit
     * @OGM\Relationship(type="PARENT", direction="INCOMING", collection=false, mappedBy="children",
     *                                  targetEntity="AdministrativeUnit")
     */
    private $parent;

    /**
     * @var AdministrativeUnit[]|Collection
     * @OGM\Relationship(type="PARENT", direction="OUTGOING", collection=true, mappedBy="parent",
     *                                  targetEntity="AdministrativeUnit")
     */
    private $children;

    /**
     * AdministrativeUnit constructor.
     */
    public function __construct()
    {
        $this->children = new Collection();
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
     * @return AdministrativeUnit
     */
    public function setId(int $id): AdministrativeUnit
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int|null $type
     * @return AdministrativeUnit
     */
    public function setType(?int $type): AdministrativeUnit
    {
        $this->type = $type;
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
     * @return AdministrativeUnit
     */
    public function setName(string $name): AdministrativeUnit
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return AdministrativeUnit
     */
    public function setTitle(?string $title): AdministrativeUnit
    {
        $this->title = $title;
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
     * @return AdministrativeUnit
     */
    public function setSlug(string $slug): AdministrativeUnit
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
     * @return AdministrativeUnit
     */
    public function setSiruta(int $siruta): AdministrativeUnit
    {
        $this->siruta = $siruta;
        return $this;
    }

    /**
     * @return array
     */
    public function getPostalCodes(): ?array
    {
        return $this->postalCodes;
    }

    /**
     * @param array $postalCodes
     * @return AdministrativeUnit
     */
    public function setPostalCodes(?array $postalCodes): AdministrativeUnit
    {
        if ($postalCodes) {
            $this->postalCodes = array_values($postalCodes);
        }
        return $this;
    }

    /**
     * @param string $postalCode
     * @return AdministrativeUnit
     */
    public function addPostalCode(string $postalCode): AdministrativeUnit
    {
        if (intval($postalCode) == 0) {
            return $this;
        }
        if (is_null($this->postalCodes)) {
            $this->postalCodes = [];
        }
        if (!in_array($postalCode, $this->postalCodes)) {
            $this->postalCodes[] = $postalCode;
        }
        return $this;
    }

    /**
     * @return AdministrativeUnit
     */
    public function getParent(): AdministrativeUnit
    {
        return $this->parent;
    }

    /**
     * @param AdministrativeUnit $parent
     * @return AdministrativeUnit
     */
    public function setParent(AdministrativeUnit $parent): AdministrativeUnit
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return AdministrativeUnit[]|Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param AdministrativeUnit[]|Collection $children
     * @return AdministrativeUnit
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }
}