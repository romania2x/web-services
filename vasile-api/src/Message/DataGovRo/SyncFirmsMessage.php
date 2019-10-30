<?php

namespace App\Message\DataGovRo;

/**
 * Class SyncFirmsMessage
 * @package App\Message\DataGovRo
 */
class SyncFirmsMessage
{
    /**
     * @var string
     */
    private $setName;

    /**
     * @var string
     */
    private $setUrl;

    /**
     * SyncFirmsMessage constructor.
     * @param $setName
     * @param $setUrl
     */
    public function __construct($setName, $setUrl)
    {
        $this->setName = $setName;
        $this->setUrl = $setUrl;
    }

    /**
     * @return string
     */
    public function getSetName(): string
    {
        return $this->setName;
    }

    /**
     * @return string
     */
    public function getSetUrl(): string
    {
        return $this->setUrl;
    }
}
