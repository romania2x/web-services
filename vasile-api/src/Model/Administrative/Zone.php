<?php

namespace App\Model\Administrative;

/**
 * Class Zone
 * @package App\Model\Administrative
 */
class Zone
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
     * @var int
     */
    protected $siruta;

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
}
