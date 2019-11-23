<?php

namespace App\MessageHandler\Crawler\DataGovRo\Streets;

use App\Message\DataGovRo\Streets\LoadStreets;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\Repository\Administrative\WayRepository;

class LoadStreetsHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var WayRepository
     */
    private $wayRepository;

    /**
     * @var \Redis
     */
    private $cache;

    /**
     * @param WayRepository $wayRepository
     * @param \Redis $cache
     */
    public function __construct(WayRepository $wayRepository, \Redis $cache)
    {
        parent::__construct();
        $this->wayRepository = $wayRepository;
        $this->cache = $cache;
    }

    /**
     * @param LoadStreets $message
     */
    public function __invoke(LoadStreets $message)
    {
        $this->log("Loading streets ...");

        $streetsData = simplexml_load_file($this->generateLocalFilePath($message->getSource()));
        $this->createProgressBar(count($streetsData->rand));

        /** @var \SimpleXMLElement $streetData */
        foreach ($streetsData->rand as $streetData) {
            $administrativeUnit = $this->getAdministrativeUnitId((int) $streetData->LOC_COD);

            if (is_null($administrativeUnit)) {
                //todo: log invalid entries
                continue;
            }

            if (is_null($this->wayRepository->createFromStreets(get_object_vars($streetData), $administrativeUnit))) {
                $this->output->write(PHP_EOL);
                $this->log('Could not add way <question>[ ' . json_encode($streetData) . '</question> ]');
            }
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
