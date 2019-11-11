<?php
declare(strict_types = 1);

namespace App\Message\DataGovRo;

/**
 * Class DataSetDetailsUpdate
 * @package App\Message\DataGovRo
 */
class DataSetDetailsUpdate
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $type;

    /**
     * LoadSetMessage constructor.
     * @param string $url
     * @param string $type
     */
    public function __construct(string $url, string $type)
    {
        $this->url  = $url;
        $this->type = $type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getType()
    {
        return $this->type;
    }
}
