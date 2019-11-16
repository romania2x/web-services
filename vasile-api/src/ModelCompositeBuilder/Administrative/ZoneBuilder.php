<?php

namespace App\ModelCompositeBuilder\Administrative;

use App\Entity\Administrative\Zone;
use App\Helpers\LanguageHelpers;

/**
 * Class ZoneBuilder
 * @package App\ModelCompositeBuilder\Administrative
 */
class ZoneBuilder
{
    /**
     * @var Zone
     */
    private $zone;

    /**
     * ZoneBuilder constructor.
     * @param Zone|null $zone
     */
    public function __construct(?Zone $zone = null)
    {
        $this->zone = $zone ?? new Zone();
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addData(string $key, string $value)
    {
        switch ($key) {
            case 'ZONA':
                $this->zone->setNationalId(intval($value));
                break;
            case 'SIRUTA':
                $this->zone->setSiruta(intval($value));
                break;
            case 'DENZONA':
                $this->zone->setName(LanguageHelpers::normalizeName($value));
                $this->zone->setSlug(LanguageHelpers::slugify($value));
                break;
            default:
                throw new \RuntimeException("Unknown field $key with value $value");
        }
    }

    /**
     * @return Zone
     */
    public function getZone(): Zone
    {
        return $this->zone;
    }

    /**
     * @param array  $row
     * @return Zone
     */
    public static function fromSiruta(array $row): Zone
    {
        $builder = new ZoneBuilder();
        foreach ($row as $key => $value) {
            $builder->addData($key, $value);
        }
        return $builder->getZone();
    }
}
