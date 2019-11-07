<?php

namespace App\Message\DataGovRo;

use App\Entity\OpenData\Source;

/**
 * Class DataSetProcess
 * @package App\Message\DataGovRo
 */
class DataSetProcess
{
    /**
     * @var int
     */
    private $sourceId;

    /**
     * DataSetProcess constructor.
     * @param Source $source
     */
    public function __construct(Source $source)
    {
        $this->sourceId = $source->getId();
    }

    /**
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * @param int $sourceId
     * @return DataSetProcess
     */
    public function setSourceId(int $sourceId): DataSetProcess
    {
        $this->sourceId = $sourceId;
        return $this;
    }
}
