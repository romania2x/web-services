<?php

namespace App\Message\DataGovRo\PostalCodes;

use App\Entity\OpenData\Source;

/**
 * Class ProcessPostalCodes
 * @package App\Message\DataGovRo\PostalCodes
 */
class ProcessPostalCodes
{
    /**
     * @var Source
     */
    private $source;

    /**
     * ProcessPostalCodes constructor.
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
