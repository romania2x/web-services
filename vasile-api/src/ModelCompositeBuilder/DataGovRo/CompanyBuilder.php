<?php

namespace App\ModelCompositeBuilder\DataGovRo;

use App\Elasticsearch\Model\Company;

/**
 * Class CompanyBuilder
 * @package App\ModelCompositeBuilder\DataGovRo
 */
class CompanyBuilder
{
    /**
     * @var Company
     */
    private $company;

    /**
     * CompanyBuilder constructor.
     */
    public function __construct()
    {
        $this->company = new Company();
    }

    /**
     * @param string $field
     * @param $value
     * @param int $timestamp
     */
    public function addData(string $field, $value, int $timestamp)
    {
        switch ($field) {
            case 'DENUMIRE':
                $this->company->setName($value);
                break;
            case 'CUI':
                $this->company->setNationalUniqueIdentification($value);
                break;
            case 'COD_INMATRICULARE':
                $this->company->setRegistrationNumber($value);
                break;
            case 'EUID':
                $this->company->setEuropeanUniqueIdentification($value);
                break;
            case 'STARE_FIRMA':
                $this->company->addStateHistory(
                    $timestamp,
                    array_map('intval', explode(',', $value))
                );
                break;
            case 'ADRESA':
                $this->company->setFullAddress($value);
                break;
            case 'JUDET':
                $this->company->setCounty($value);
                break;
            case 'LOCALITATE':
                $this->company->setLocality($value);
                break;
            default:
                die("Field $field is not recognized\n");
        }
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }
}
