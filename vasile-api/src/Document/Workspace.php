<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Workspace
 * @package App\Document
 *
 * @ODM\Document(collection="workspaces")
 */
class Workspace
{
    /**
     * @var string
     * @ODM\Id(strategy="auto")
     */
    private $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $name;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $description;

    /**
     * @var bool
     * @ODM\Field(type="bool")
     */
    private $mapLocked = false;

    /**
     * @var array
     * @ODM\Field(type="array")
     */
    private $mapBounds;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Workspace
     */
    public function setId(string $id): Workspace
    {
        $this->id = $id;
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
     * @return Workspace
     */
    public function setName(string $name): Workspace
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Workspace
     */
    public function setDescription(string $description): Workspace
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMapLocked(): bool
    {
        return $this->mapLocked;
    }

    /**
     * @param bool $mapLocked
     * @return Workspace
     */
    public function setMapLocked(bool $mapLocked): Workspace
    {
        $this->mapLocked = $mapLocked;
        return $this;
    }

    /**
     * @return array
     */
    public function getMapBounds(): array
    {
        return $this->mapBounds;
    }

    /**
     * @param array $mapBounds
     * @return Workspace
     */
    public function setMapBounds(array $mapBounds): Workspace
    {
        $this->mapBounds = $mapBounds;
        return $this;
    }
}
