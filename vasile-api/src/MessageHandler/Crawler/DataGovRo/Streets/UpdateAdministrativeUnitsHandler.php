<?php

namespace App\MessageHandler\Crawler\DataGovRo\Streets;

use App\Helpers\LanguageHelpers;
use App\Message\DataGovRo\Streets\UpdateAdministrativeUnits;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\Repository\Administrative\UnitRepository;

class UpdateAdministrativeUnitsHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var UnitRepository
     */
    private $administrativeUnitRepository;

    /**
     * @var \Redis
     */
    private $cache;

    /**
     * @param UnitRepository $unitRepository
     * @param \Redis $cache
     */
    public function __construct(UnitRepository $unitRepository, \Redis $cache)
    {
        parent::__construct();
        $this->administrativeUnitRepository = $unitRepository;
        $this->cache = $cache;
    }

    /**
     * @param UpdateAdministrativeUnits $message
     */
    public function __invoke(UpdateAdministrativeUnits $message)
    {
        $this->log("Loading localities ...");

        $localitiesData = simplexml_load_file($this->generateLocalFilePath($message->getSource()));

        $this->createProgressBar(count($localitiesData->rand));

        /** @var \SimpleXMLElement $localityData */
        foreach ($localitiesData->rand as $localityData) {
            if ($unit = $this->administrativeUnitRepository->updateFromStreetData($localityData)) {
            } else {
                $this->output->write(PHP_EOL);
                $name = LanguageHelpers::asciiTranslit($localityData->DENUMIRE);
                $this->output->write(PHP_EOL);
                $this->log("Could not find <question>[ $name - Siruta: {$localityData->COD_SIRUTA}, Postal: {$localityData->COD_POSTAL}, Primarie: {$localityData->ARE_PRIMARIE}, CUI: {$localityData->COD_FISCAL_PRIMARIE} ]</question>");
            }
            $this->progressBar->advance();
            $this->cleanMemory();
        }

        $this->finishProgressBar();
    }
}
