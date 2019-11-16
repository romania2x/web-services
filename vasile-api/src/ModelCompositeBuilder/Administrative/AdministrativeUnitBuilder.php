<?php

namespace App\ModelCompositeBuilder\Administrative;

use App\Entity\Administrative\AdministrativeUnit;
use App\Helpers\LanguageHelpers;

/**
 * Class AdministrativeUnitBuilder
 * @package App\ModelCompositeBuilder\Administrative
 */
class AdministrativeUnitBuilder
{
    /**
     * @var AdministrativeUnit
     */
    private $administrativeUnit;

    /**
     * AdministrativeUnitBuilder constructor.
     * @param AdministrativeUnit|null $administrativeUnit
     */
    public function __construct(?AdministrativeUnit $administrativeUnit = null)
    {
        $this->administrativeUnit = $administrativeUnit ?? new AdministrativeUnit();
    }

    /**
     * @return AdministrativeUnit
     */
    public function getAdministrativeUnit(): AdministrativeUnit
    {
        return $this->administrativeUnit;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addData(string $key, string $value)
    {
        switch ($key) {
            case 'DENLOC':
                $this->administrativeUnit->setName(LanguageHelpers::normalizeName($value));
                $this->administrativeUnit->setSlug(LanguageHelpers::slugify($value));
                break;
            case 'SIRUTA':
                $this->administrativeUnit->setSiruta(intval($value));
                break;
            case 'TIP':
                $this->administrativeUnit->setType(intval($value));
                break;
            default:
                continue;
//                throw new \RuntimeException("Unknown field $key with value $value");
        }
    }

    /**
     * @param array $row
     * @return AdministrativeUnit
     */
    public static function fromSiruta(array $row): AdministrativeUnit
    {
        $builder = new AdministrativeUnitBuilder();
        foreach ($row as $key => $value) {
            $builder->addData($key, $value);
        }
        return $builder->getAdministrativeUnit();
    }
}
