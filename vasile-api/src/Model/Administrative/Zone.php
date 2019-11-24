<?php

namespace App\Model\Administrative;


/**
 * Class Zone
 * @package App\Model\Administrative
 */
class Zone extends Administrative
{
    /**
     * @var int
     */
    protected $nationalId;

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
}
