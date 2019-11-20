<?php

namespace App\MessageHandler\Crawler\DataGovRo\Streets;

use App\Helpers\LanguageHelpers;
use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Streets\ProcessStreets;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\Repository\Administrative\UnitRepository;
use App\Repository\Administrative\WayRepository;

/**
 * Class ProcessStreetsHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Streets
 */
class ProcessStreetsHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var \Redis
     */
    private $cache;

    /**
     * @var UnitRepository
     */
    private $administrativeUnitRepository;

    /**
     * @var WayRepository
     */
    private $wayRepository;

    /**
     * ProcessStreetsHandler constructor.
     * @param UnitRepository $administrativeUnitRepository
     * @param WayRepository  $wayRepository
     * @param \Redis         $cache
     */
    public function __construct(
        UnitRepository $administrativeUnitRepository,
        WayRepository $wayRepository,
        \Redis $cache
    ) {
        parent::__construct();
        $this->administrativeUnitRepository = $administrativeUnitRepository;
        $this->wayRepository                = $wayRepository;
        $this->cache                        = $cache;
    }

    /**
     * @param ProcessStreets $message
     * @throws \Exception
     */
    public function __invoke(ProcessStreets $message)
    {
        $this->clearCache();
        $this->log("Processing streets pack {$message->getSource()->getTitle()}");
        $this->updateLocalities($message->getSource());
        $this->loadStreets($message->getSource());
    }

    private function loadStreets(Source $source)
    {
        $this->log("Loading streets ...");
        /** @var Source $localities */
        $streets = $source->getChildren()->filter(function (Source $child) {
            return strpos(strtolower($child->getTitle()), 'nomarteremf') !== false;
        })->first();

        $streetsData = simplexml_load_file($this->generateLocalFilePath($streets));
        $this->createProgressBar(count($streetsData->rand));

        /** @var \SimpleXMLElement $streetData */
        foreach ($streetsData->rand as $streetData) {
            $administrativeUnit = $this->getAdministrativeUnitId((int) $streetData->LOC_COD);

            if (is_null($this->wayRepository->createFromStreets(get_object_vars($streetData), $administrativeUnit))) {
                $this->output->write(PHP_EOL);
                $this->log('Could not add way <question>[ ' . json_encode($streetData) . '</question> ]');
            }
            $this->progressBar->advance();
        }
        $this->finishProgressBar();
    }

    /**
     * @param Source $source
     * @throws \Exception
     */
    private function updateLocalities(Source $source)
    {
        $this->log("Loading localities ...");
        /** @var Source $localities */
        $localities = $source->getChildren()->filter(function (Source $child) {
            return strpos(strtolower($child->getTitle()), 'nomlocalitati_') !== false;
        })->first();

        $localitiesData = simplexml_load_file($this->generateLocalFilePath($localities));

        $this->createProgressBar(count($localitiesData->rand));

        /** @var \SimpleXMLElement $localityData */
        foreach ($localitiesData->rand as $localityData) {
            if ($unit = $this->administrativeUnitRepository->updateFromStreetData(get_object_vars($localityData))) {
                $this->cacheLocalityId((int) $localityData->COD, $unit->getId());
            } else {
                $this->output->write(PHP_EOL);
                $name = LanguageHelpers::asciiTranslit($localityData->DENUMIRE);
                $this->output->write(PHP_EOL);
                $this->log("Could not find <question>[ $name - Siruta: {$localityData->COD_SIRUTA}, Postal: {$localityData->COD_POSTAL}, Primarie: {$localityData->ARE_PRIMARIE}, CUI: {$localityData->COD_FISCAL_PRIMARIE} ]</question>");
            }
            $this->progressBar->advance();
        }
        $this->finishProgressBar();
    }

    /**
     * @param int $localityId
     * @param int $administrativeUnitId
     */
    private function cacheLocalityId(int $localityId, int $administrativeUnitId)
    {
        $this->cache->set(implode(',', ['streets', 'locality_id', $localityId]), $administrativeUnitId);
    }

    /**
     * @param int $localityId
     * @return int|null
     */
    private function getAdministrativeUnitId(int $localityId): ?int
    {
        $entry = $this->cache->get(implode(',', ['streets', 'locality_id', $localityId]));
        if ($entry === false) {
            return null;
        }
        return $entry;
    }

    private function clearCache()
    {
        $this->cache->del($this->cache->keys('streets.locality_id.*'));
    }
}
