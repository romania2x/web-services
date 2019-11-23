<?php

namespace App\Model\Administrative;
/**
 * Class WayNumber
 * @package App\Message\DataGovRo\Streets
 */
class WayNumber
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var int
     */
    protected $number;

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
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
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
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }
}
