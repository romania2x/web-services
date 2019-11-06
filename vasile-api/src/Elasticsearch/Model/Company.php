<?php

namespace App\Elasticsearch\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Company
 * @package App\Elasticsearch\Model
 */
class Company
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $name;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $nationalUniqueIdentification;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $europeanUniqueIdentification;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $registrationNumber;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $fullAddress;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $county;
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $locality;
    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $stateHistory = [];

    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $sources = [];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
     */
    public function setStateHistory(array $stateHistory): Company
    {
        $this->stateHistory = $stateHistory;
        return $this;
    }

    /**
     * @param int $timestamp
     * @param array $states
     * @return Company
     */
    public function addStateHistory(int $timestamp, array $states): Company
    {
        $this->stateHistory[$timestamp] = $states;
        return $this;
    }

    /**
     * @return array
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @param array $sources
     * @return Company
     */
    public function setSources(array $sources): Company
    {
        $this->sources = $sources;
        return $this;
    }

    /**
     * @param string $source
     * @return Company
     */
    public function addSource(string $source): Company
    {
        if (!in_array($source, $this->sources)) {
            $this->sources[] = $source;
        }
        return $this;
    }
}
