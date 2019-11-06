<?php
declare(strict_types = 1);

namespace App\Message\DataGovRo\Companies;

/**
 * Class LoadCompaniesSet
 * @package App\Message\DataGovRo\Companies
 */
class LoadCompaniesSet
{
    /**
     * @var string
     */
    private $source;

    /**
     * LoadSetMessage constructor.
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function getSource(): string
    {
        return $this->source;
    }
}
