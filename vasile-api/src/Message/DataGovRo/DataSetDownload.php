<?php

namespace App\Message\DataGovRo;

use App\Entity\OpenData\Source;

/**
 * Class DownloadCompaniesSubset
 * @package App\Message\DataGovRo
 */
class DataSetDownload
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
