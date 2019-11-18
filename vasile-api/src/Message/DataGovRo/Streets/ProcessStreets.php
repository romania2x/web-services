<?php
declare(strict_types = 1);

namespace App\Message\DataGovRo\Streets;

use App\Entity\OpenData\Source;

/**
 * Class ProcessStreets
 * @package App\Message\DataGovRo\Streets
 */
class ProcessStreets
{
    /**
     * @var Source
     */
    private $source;

    /**
     * ProcessStreets constructor.
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
