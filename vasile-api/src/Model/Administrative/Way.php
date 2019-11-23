<?php

declare(strict_types = 1);

namespace App\Model\Administrative;

/**
 * Class Way
 * @package App\Model\Administrative
 */
class Way
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $title;
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
    protected $countyId;
    /**
     * @var string
     */
    protected $postalCode;

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

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return Way
     */
    public function setPostalCode(string $postalCode): Way
    {
        $this->postalCode = $postalCode;
        return $this;
    }
}
