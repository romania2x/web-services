<?php

declare(strict_types = 1);

namespace App\Model\Administrative;

/**
 * Class Way
 * @package App\Model\Administrative
 */
class Way extends Administrative
{
    /**
     * @var string
     */
    protected $title;
    /**
     * @var int
     */
    protected $countyId;

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
}
