<?php

namespace App\Message\DataGovRo\Companies;

use App\Entity\OpenData\Source;

/**
 * Class DownloadCompaniesSubset
 * @package App\Message\DataGovRo\Companies
 */
class DownloadCompaniesSubset
{
    /**
     * @var Source
     */
    private $source;

    /**
     * DownloadCompaniesSubset constructor.
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
