<?php
declare(strict_types = 1);

namespace App\Message\DataGovRo\Siruta;

use App\Entity\OpenData\Source;
use App\Message\Traits\SourceAwareTrait;

/**
 * Class ProcessSiruta
 * @package App\Message\DataGovRo\Siruta
 */
class ProcessSiruta
{
    use SourceAwareTrait;

    /**
     * ProcessSiruta constructor.
     * @param Source $source
     */
    public function __construct(Source $source)
    {
        $this->source = $source;
    }
}
