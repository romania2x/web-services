<?php

namespace App\Model\Administrative;

/**
 * Class WayNumber
 * @package App\Message\DataGovRo\Streets
 */
class WayNumber extends Administrative
{
    /**
     * @var int
     */
    protected $number;

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
}
