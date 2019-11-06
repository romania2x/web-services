<?php

namespace App\ModelCompositeBuilder\DataGovRo;

/**
 * Class CompanyBuilder
 * @package App\ModelCompositeBuilder\DataGovRo
 */
class CompanyBuilder
{
    private $name;
    private $nationalUniqueIdentification;
    private $europeanUniqueIdentification;
    private $registrationNumber;
    private $fullAddress;
    private $county;
    private $locality;
    private $stateHistory = [];

    /**
     * @param string $field
     * @param $value
     * @param int $timestamp
     */
    public function addData(string $field, $value, int $timestamp)
    {
        switch ($field) {
            case 'DENUMIRE':
                $this->name = $value;
                break;
            case 'CUI':
                $this->nationalUniqueIdentification = $value;
                break;
            case 'COD_INMATRICULARE':
                $this->registrationNumber = $value;
                break;
            case 'EUID':
                $this->europeanUniqueIdentification = $value;
                break;
            case 'STARE_FIRMA':
                $this->stateHistory[$timestamp] = array_map('intval', explode(',', $value));
                break;
            case 'ADRESA':
                $this->fullAddress = $value;
                break;
            case 'JUDET':
                $this->county = $value;
                break;
            case 'LOCALITATE':
                $this->locality = $value;
                break;
            default:
                die("Field $field is not recognized\n");
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return CompanyBuilder
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNationalUniqueIdentification()
    {
        return $this->nationalUniqueIdentification;
    }

    /**
     * @param mixed $nationalUniqueIdentification
     * @return CompanyBuilder
     */
    public function setNationalUniqueIdentification($nationalUniqueIdentification)
    {
        $this->nationalUniqueIdentification = $nationalUniqueIdentification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEuropeanUniqueIdentification()
    {
        return $this->europeanUniqueIdentification;
    }

    /**
     * @param mixed $europeanUniqueIdentification
     * @return CompanyBuilder
     */
    public function setEuropeanUniqueIdentification($europeanUniqueIdentification)
    {
        $this->europeanUniqueIdentification = $europeanUniqueIdentification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistrationNumber()
    {
        return $this->registrationNumber;
    }

    /**
     * @param mixed $registrationNumber
     * @return CompanyBuilder
     */
    public function setRegistrationNumber($registrationNumber)
    {
        $this->registrationNumber = $registrationNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullAddress()
    {
        return $this->fullAddress;
    }

    /**
     * @param mixed $fullAddress
     * @return CompanyBuilder
     */
    public function setFullAddress($fullAddress)
    {
        $this->fullAddress = $fullAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param mixed $county
     * @return CompanyBuilder
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param mixed $locality
     * @return CompanyBuilder
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
        return $this;
    }

    /**
     * @return array
     */
    public function getStateHistory(): array
    {
        return $this->stateHistory;
    }

    /**
     * @param array $stateHistory
     * @return CompanyBuilder
     */
    public function setStateHistory(array $stateHistory): CompanyBuilder
    {
        $this->stateHistory = $stateHistory;
        return $this;
    }

    public function getData()
    {
        return [
            'name' => $this->name,
            'fullAddress' => $this->fullAddress,
            'county' => $this->county,
            'locality' => $this->locality,
            'nationalUniqueIdentification' => $this->nationalUniqueIdentification,
            'europeanUniqueIdentification' => $this->europeanUniqueIdentification,
            'registrationNumber' => $this->registrationNumber,
            'stateHistory' => $this->stateHistory
        ];
    }
}
