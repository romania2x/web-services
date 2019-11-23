<?php

namespace App\Message\DataGovRo\Streets;

use App\Entity\OpenData\Source;

class LoadStreetsNumbers
{
    /**
     * @var Source
     */
    private $source;

    /**
     * LoadStreetsNumbers constructor.
     * @param Source $source
     */
    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * @return Source
     */
    public function getSource()
    {
        return $this->source;
    }
}
