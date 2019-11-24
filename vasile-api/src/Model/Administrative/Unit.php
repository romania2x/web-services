<?php

declare(strict_types = 1);

namespace App\Model\Administrative;

/**
 * Class AdministrativeUnit
 * @package App\Model\Administrative
 */
class Unit extends Administrative
{
    /**
     * @var string|null
     */
    protected $title;
    /**
     * @var string|null
     */
    protected $type;
    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $rank;

    /**
     * @var bool
     */
    protected $fictive;

    /**
     * @var bool
     */
    protected $townHall;

    /**
     * @var string
     */
    protected $townHallFiscalCode;

    /**
     * @var int
     */
    protected $structuralId;

    /**
     * @var int
     */
    protected $policeId;

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
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Unit
     */
    public function setType(?string $type): Unit
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     * @return Unit
     */
    public function setEnvironment(string $environment): Unit
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFictive(): bool
    {
        return $this->fictive;
    }

    /**
     * @param bool $fictive
     * @return Unit
     */
    public function setFictive(bool $fictive): Unit
    {
        $this->fictive = $fictive;
        return $this;
    }

    /**
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * @param string $rank
     * @return Unit
     */
    public function setRank(string $rank): Unit
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTownHall(): bool
    {
        return $this->townHall;
    }

    /**
     * @param bool $townHall
     * @return Unit
     */
    public function setTownHall(bool $townHall): Unit
    {
        $this->townHall = $townHall;
        return $this;
    }

    /**
     * @return string
     */
    public function getTownHallFiscalCode(): string
    {
        return $this->townHallFiscalCode;
    }

    /**
     * @param string $townHallFiscalCode
     * @return Unit
     */
    public function setTownHallFiscalCode(string $townHallFiscalCode): Unit
    {
        $this->townHallFiscalCode = $townHallFiscalCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getStructuralId(): int
    {
        return $this->structuralId;
    }

    /**
     * @param int $structuralId
     * @return Unit
     */
    public function setStructuralId(int $structuralId): Unit
    {
        $this->structuralId = $structuralId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPoliceId(): int
    {
        return $this->policeId;
    }

    /**
     * @param int $policeId
     * @return Unit
     */
    public function setPoliceId(int $policeId): Unit
    {
        $this->policeId = $policeId;
        return $this;
    }
}
