<?php

namespace App\Message\DataGovRo\Siruta;

use App\Entity\OpenData\Source;

/**
 * Class ProcessSiruta
 * @package App\Message\DataGovRo\Siruta
 */
class ProcessSiruta
{
    /**
     * @var Source
     */
    private $source;

    /**
     * ProcessSiruta constructor.
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
