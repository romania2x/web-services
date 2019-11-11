<?php

namespace App\ModelCompositeBuilder\Administrative;

use App\Entity\Administrative\County;

/**
 * Class CountyBuilder
 * @package App\ModelCompositeBuilder\Administrative
 */
class CountyBuilder
{
    /**
     * @var County
     */
    private $county;

    /**
     * CountyBuilder constructor.
     * @param County|null $county
     */
    public function __construct(?County $county = null)
    {
        $this->county = $county ?? new County();
    }

    /**
     * @return County
     */
    public function getCounty(): County
    {
        return $this->county;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addData(string $key, string $value)
    {
        switch ($key) {
            case 'JUD':
                $this->county->setNationalId($value);
                break;
            case 'DENJ':
                $this->county->setName($value);
                break;
            case 'MNEMONIC':
                $this->county->setMnemonic($value);
                break;
            case 'FSJ':
                $this->county->setSortingIndex($value);
                break;
            case 'ZONA':
                //noop
                break;
            default:
                throw new \RuntimeException("Unknown field $key with value $value");
        }
    }

    /**
     * @param array $row
     * @return County
     */
    public static function fromSiruta(array $row): County
    {
        $builder = new CountyBuilder();
        foreach ($row as $key => $value) {
            $builder->addData($key, $value);
        }
        return $builder->getCounty();
    }
}
