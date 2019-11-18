<?php

declare(strict_types = 1);

namespace App\Model\Administrative;

/**
 * Class AdministrativeUnit
 * @package App\Model\Administrative
 */
class Unit
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string|null
     */
    protected $title;
    /**
     * @var string
     */
    protected $slug;
    /**
     * @var int
     */
    protected $siruta;
    /**
     * @var array
     */
    protected $postalCodes;
    /**
     * @var int|null
     */
    protected $type;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Unit
     */
    public function setId(int $id): Unit
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
     * @return Unit
     */
    public function setType(?int $type): Unit
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
     * @return Unit
     */
    public function setName(string $name): Unit
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
     * @return Unit
     */
    public function setTitle(?string $title): Unit
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
     * @return Unit
     */
    public function setSlug(string $slug): Unit
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
     * @return Unit
     */
    public function setSiruta(int $siruta): Unit
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
     * @return Unit
     */
    public function setPostalCodes(?array $postalCodes): Unit
    {
        if ($postalCodes) {
            $this->postalCodes = array_values($postalCodes);
        }
        return $this;
    }

    /**
     * @param string $postalCode
     * @return Unit
     */
    public function addPostalCode(string $postalCode): Unit
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
}
