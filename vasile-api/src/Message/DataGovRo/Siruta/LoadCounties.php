<?php
declare(strict_types = 1);

namespace App\Message\DataGovRo\Siruta;

use App\Entity\OpenData\Source;
use App\Message\Traits\SourceAwareTrait;

/**
 * Class LoadCounties
 * @package App\Message\DataGovRo\Siruta
 */
class LoadCounties
{
    use SourceAwareTrait;
    /**
     * @var string
     */
    private $encoding;

    /**
     * @var string
     */
    private $separator;

    /**
     * LoadCounties constructor.
     * @param Source $source
     * @param string $encoding
     * @param string $separator
     */
    public function __construct(Source $source, string $encoding, string $separator)
    {
        $this->source    = $source;
        $this->encoding  = $encoding;
        $this->separator = $separator;
    }

    /**
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * @return string
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }
}
