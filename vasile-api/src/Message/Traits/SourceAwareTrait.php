<?php

namespace App\Message\Traits;

use App\Entity\OpenData\Source;

/**
 * Trait SourceAwareTrait
 * @package App\Message\Traits
 */
trait SourceAwareTrait
{
    /**
     * @var Source
     */
    private $source;

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }
}
