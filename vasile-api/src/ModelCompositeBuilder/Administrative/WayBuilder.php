<?php

namespace App\ModelCompositeBuilder\Administrative;

use App\Entity\Administrative\Way;
use App\Helpers\LanguageHelpers;

/**
 * Class WayBuilder
 * @package App\ModelCompositeBuilder\Administrative
 */
class WayBuilder
{
    /**
     * @var Way
     */
    private $way;

    /**
     * WayBuilder constructor.
     * @param Way|null $way
     */
    public function __construct(?Way $way = null)
    {
        $this->way = $way ?? new Way();
    }

    /**
     * @return Way
     */
    public function getWay(): Way
    {
        return $this->way;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addStreetsData(string $key, string $value)
    {
        switch ($key) {
            case 'DENUMIRE':
                $this->way->setName(LanguageHelpers::normalizeName($value));
                $this->way->setSlug(LanguageHelpers::slugify($value));
                break;
            case 'TAT_COD':
                $this->way->setTitle(strtolower($value));
                break;
            case 'COD':
                $this->way->setCountyId($value);
                break;
            default:
                continue;
        }
    }

    /**
     * @param \SimpleXMLElement $element
     * @return Way
     */
    public static function fromStreetsIndex(\SimpleXMLElement $element): Way
    {
        $builder = new self();
        foreach (get_object_vars($element) as $key => $value) {
            $builder->addStreetsData($key, $value);
        }
        return $builder->getWay();
    }
}
