<?php

namespace App\Model\Administrative;

/**
 * Class County
 * @package App\Model\Administrative
 */
class County
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $nationalId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $mnemonic;

    /**
     * @var int
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
}
