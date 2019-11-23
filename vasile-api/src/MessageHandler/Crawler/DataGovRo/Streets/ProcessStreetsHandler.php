<?php

namespace App\MessageHandler\Crawler\DataGovRo\Streets;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Streets\LoadStreets;
use App\Message\DataGovRo\Streets\LoadStreetsNumbers;
use App\Message\DataGovRo\Streets\ProcessStreets;
use App\Message\DataGovRo\Streets\UpdateAdministrativeUnits;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;

class ProcessStreetsHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var \Redis
     */
    private $cache;

    /**
     * @param \Redis $cache
     */
    public function __construct(\Redis $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

    /**
     * @param ProcessStreets $message
     * @throws \Exception
     */
    public function __invoke(ProcessStreets $message)
    {
        $this->clearCache();

        $this->log("Processing streets pack {$message->getSource()->getTitle()}");

        $unitsSource = $message->getSource()->getChildren()->filter(function (Source $child) {
            return strpos(strtolower($child->getTitle()), 'nomlocalitati_') !== false;
        })->first();

        $this->messageBus->dispatch(new UpdateAdministrativeUnits($unitsSource));

        $streetsSource = $message->getSource()->getChildren()->filter(function (Source $child) {
            return strpos(strtolower($child->getTitle()), 'nomarteremf') !== false;
        })->first();

        $this->messageBus->dispatch(new LoadStreets($streetsSource));

        $streetsNumbersSource = $message->getSource()->getChildren()->filter(function (Source $child) {
            return strpos(strtolower($child->getTitle()), 'nomcodnrstrazi') !== false;
        })->first();

        $this->messageBus->dispatch(new LoadStreetsNumbers($streetsNumbersSource));
    }

    /**
     * Clear mappings cache
     */
    private function clearCache()
    {
        $this->cache->del($this->cache->keys('streets.locality_id.*'));
    }
}
