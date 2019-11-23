<?php

namespace App\MessageHandler\Crawler\DataGovRo\Streets;

use App\Message\DataGovRo\Streets\LoadStreetsNumbers;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\Repository\Administrative\WayNumberRepository;

/**
 * Class LoadStreetsNumbersHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Streets
 */
class LoadStreetsNumbersHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var WayNumberRepository
     */
    private $wayNumberRepository;

    /**
     * @var \Redis
     */
    private $cache;

    /**
     * @param \Redis $cache
     * @param WayNumberRepository $wayNumberRepository
     */
    public function __construct(\Redis $cache, WayNumberRepository $wayNumberRepository)
    {
        $this->cache = $cache;
        $this->wayNumberRepository = $wayNumberRepository;
        parent::__construct();
    }

    /**
     * @param LoadStreetsNumbers $message
     */
    public function __invoke(LoadStreetsNumbers $message)
    {
        $this->log('Loading streets numbers ...');

        $numbersData = simplexml_load_file($this->generateLocalFilePath($message->getSource()));
        $this->createProgressBar(count($numbersData->rand));

        /** @var \SimpleXMLElement $numberSet */
        foreach ($numbersData->rand as $numberSet) {
            $administrativeUnit = $this->getAdministrativeUnitId((int) $numberSet->LOC_COD);
            if (is_null($administrativeUnit)) {
                $this->log("Cannot find parent administrative unit " . json_encode($numberSet));
                $this->progressBar->advance();
                continue;
            }
            $this->wayNumberRepository->createFromStreets(get_object_vars($numberSet), $administrativeUnit);
            $this->progressBar->advance();
        }
        $this->finishProgressBar();
    }


    /**
     * @param int $localityId
     * @return int|null
     */
    private function getAdministrativeUnitId(int $localityId): ?int
    {
        $entry = $this->cache->get(implode('.', ['streets', 'locality_id', $localityId]));
        if ($entry === false) {
            return null;
        }
        return $entry;
    }

}
