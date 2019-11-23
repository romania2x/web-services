<?php

namespace App\Message\DataGovRo\Streets;

use App\Entity\OpenData\Source;

class UpdateAdministrativeUnits
{
    /**
     * @var Source
     */
    private $source;

    /**
     * @param Source $source
     */
    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }
}
