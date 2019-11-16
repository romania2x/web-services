<?php

namespace App\MessageHandler\Crawler\DataGovRo\Streets;

use App\Entity\Administrative\AdministrativeUnit;
use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Streets\ProcessStreets;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\ModelCompositeBuilder\Administrative\AdministrativeUnitBuilder;
use App\ModelCompositeBuilder\Administrative\WayBuilder;
use App\Repository\Entity\Administrative\AdministrativeUnitRepository;
use App\Repository\Entity\Administrative\WayRepository;

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
     * @var AdministrativeUnitRepository
     */
    private $administrativeUnitRepository;

    /**
     * @var WayRepository
     */
    private $wayRepository;

    /**
     * ProcessStreetsHandler constructor.
     * @param AdministrativeUnitRepository $administrativeUnitRepository
     * @param WayRepository                $wayRepository
     * @param \Redis                       $cache
     */
    public function __construct(
        AdministrativeUnitRepository $administrativeUnitRepository,
        WayRepository $wayRepository,
        \Redis $cache
    ) {
        parent::__construct();
        $this->wayRepository                = $wayRepository;
        $this->administrativeUnitRepository = $administrativeUnitRepository;
        $this->cache                        = $cache;
    }

    public function __invoke(ProcessStreets $message)
    {
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
        $progress    = $this->createProgressBar(count($streetsData->rand));
        $progress->start();

        /** @var \SimpleXMLElement $streetData */
        foreach ($streetsData->rand as $streetData) {
            /** @var AdministrativeUnit $administrativeUnit */
            $administrativeUnit = $this->administrativeUnitRepository->find(
                $this->getAdministrativeUnitId((int) $streetData->LOC_COD)
            );
            if (is_null($administrativeUnit)) {
                $this->log("Could not find admin unit for " . json_encode($streetData));
                continue;
            }
            $way = WayBuilder::fromStreetsIndex($streetData);
            $this->wayRepository->createOrUpdate($way, $administrativeUnit);

            $this->graphEntityManager->clear();
            $progress->advance();
        }
        $progress->finish();
        $this->output->write(PHP_EOL);
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

        $progress = $this->createProgressBar(count($localitiesData->rand));
        $progress->start();

        /** @var \SimpleXMLElement $localityData */
        foreach ($localitiesData->rand as $localityData) {
            $administrativeUnit = AdministrativeUnitBuilder::fromStreetsIndex($localityData);
            $administrativeUnit = $this->administrativeUnitRepository->createOrUpdate($administrativeUnit);
            $this->cacheLocalityId((int) $localityData->COD, $administrativeUnit->getId());
            $this->graphEntityManager->clear();
            $progress->advance();
        }
        $progress->finish();
        $this->output->write(PHP_EOL);
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
     * @return int
     */
    private function getAdministrativeUnitId(int $localityId): int
    {
        return $this->cache->get(implode(',', ['streets', 'locality_id', $localityId]));
    }
}
