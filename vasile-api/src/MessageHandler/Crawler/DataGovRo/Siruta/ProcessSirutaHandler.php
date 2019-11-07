<?php

namespace App\MessageHandler\Crawler\DataGovRo\Siruta;

use App\Constants\Administrative\AdministrativeUnitType;
use App\Entity\Administrative\AdministrativeUnit;
use App\Entity\Administrative\County;
use App\Entity\OpenData\Source;
use App\Helpers\LanguageHelpers;
use App\Message\DataGovRo\Siruta\ProcessSiruta;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\ModelCompositeBuilder\Administrative\AdministrativeUnitBuilder;
use App\ModelCompositeBuilder\Administrative\CountyBuilder;
use App\ModelCompositeBuilder\Administrative\ZoneBuilder;
use App\Repository\Entity\Administrative\AdministrativeUnitRepository;
use App\Repository\Entity\Administrative\CountyRepository;
use App\Repository\Entity\Administrative\ZoneRepository;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Class ProcessSirutaHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Siruta
 */
class ProcessSirutaHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    private const ENCODING = 'ISO-8859-2';

    /**
     * @var ZoneRepository
     */
    private $zoneRepository;
    /**
     * @var CountyRepository
     */
    private $countyRepository;
    /**
     * @var AdministrativeUnitRepository
     */
    private $administrativeUnitRepository;

    /**
     * ProcessSirutaHandler constructor.
     * @param ZoneRepository               $zoneRepository
     * @param CountyRepository             $countyRepository
     * @param AdministrativeUnitRepository $administrativeUnitRepository
     */
    public function __construct(
        ZoneRepository $zoneRepository,
        CountyRepository $countyRepository,
        AdministrativeUnitRepository $administrativeUnitRepository
    ) {
        parent::__construct();
        $this->zoneRepository               = $zoneRepository;
        $this->countyRepository             = $countyRepository;
        $this->administrativeUnitRepository = $administrativeUnitRepository;
    }

    /**
     * @param ProcessSiruta $message
     */
    public function __invoke(ProcessSiruta $message)
    {
        $this->processZones($message->getSource());
        $this->processCounties($message->getSource());
        $this->processAdministrativeUnits($message->getSource());
    }

    /**
     * @param Source $source
     */
    private function processZones(Source $source)
    {
        $this->log('Processing zones');
        /** @var Source $zonesSirutaSource */
        $zonesSirutaSource = $source->getChildren()->filter(function (Source $child) {
            return $child->getTitle() == 'siruta-zone.csv';
        })->first();

        $progress = $this->createProgressBar($this->getTotalNoLines($zonesSirutaSource) + 1);

        $this->processSiruta(
            $zonesSirutaSource,
            function (array $row) use ($progress) {
                $this->zoneRepository->createOrUpdate(ZoneBuilder::fromSiruta($row));
                $progress->advance();
            }
        );
        $progress->finish();
        $this->output->write(PHP_EOL);
    }

    /**
     * @param Source $source
     */
    private function processCounties(Source $source)
    {
        //load counties
        $this->log('Processing counties');
        /** @var Source $countiesSirutaSource */
        $countiesSirutaSource = $source->getChildren()->filter(function (Source $child) {
            return $child->getTitle() == 'siruta-judete.csv';
        })->first();

        $progress = $this->createProgressBar($this->getTotalNoLines($countiesSirutaSource) + 1);

        $this->processSiruta(
            $countiesSirutaSource,
            function (array $row) use ($progress) {
                $this->countyRepository->createOrUpdate(
                    CountyBuilder::fromSiruta($row),
                    $this->zoneRepository->findOneBy(['nationalId' => intval($row['ZONA'])])
                );
                $progress->advance();
            }
        );
        $progress->finish();
        $this->output->write(PHP_EOL);
    }

    /**
     * @param Source $source
     */
    private function processAdministrativeUnits(Source $source)
    {
        $this->log('Processing administrative units');
        /** @var Source $countiesSirutaSource */
        $sirutaSource = $source->getChildren()->filter(function (Source $child) {
            return $child->getTitle() == 'siruta.csv';
        })->first();

        $progress = $this->createProgressBar($this->getTotalNoLines($sirutaSource) + 1);

        $this->processSiruta(
            $sirutaSource,
            function (array $row) use ($progress) {
                $administrativeUnit = $this->administrativeUnitRepository->createOrUpdate(AdministrativeUnitBuilder::fromSiruta($row));

                if ($administrativeUnit->getType() == AdministrativeUnitType::COUNTY) {
                    /** @var County $county */
                    $county = $this->countyRepository->findOneBy(['nationalId' => intval($row['JUD'])]);
                    $county->setAdministrativeUnit($administrativeUnit);
                    $this->countyRepository->persist($county, true);
                } else {
                    /** @var AdministrativeUnit $parent */
                    $parent = $this->administrativeUnitRepository->getCachedBySiruta(intval($row['SIRSUP']));
                    if (is_null($parent)) {
                        $this->log('Parent not found for ' . json_encode($row));
                        return;
                    }
                    $administrativeUnit->setParent($parent);
                    $this->administrativeUnitRepository->persist($administrativeUnit, true);
                }
                $this->graphEntityManager->clear();
                $progress->advance();
            }
        );
        $progress->finish();
        $this->output->write(PHP_EOL);
    }

    /**
     * @param Source        $source
     * @param callable|null $rowCallback
     */
    private function processSiruta(Source $source, $rowCallback = null)
    {
        $fileHandle = fopen($this->generateLocalFilePath($source), 'r');
        $keys       = [];
        while ($row = fgets($fileHandle)) {
            $row = LanguageHelpers::asciiTranslit($row, self::ENCODING);
            $row = str_getcsv($row, ";");
            if (count($keys) == 0) {
                $keys = $row;
                continue;
            }
            $row = array_map('trim', $row);
            $row = array_combine($keys, $row);
            if ($rowCallback) {
                $rowCallback($row);
            }
        }
        fclose($fileHandle);
    }

    /**
     * @param Source $source
     * @return int
     */
    private function getTotalNoLines(Source $source): int
    {
        $count  = 0;
        $handle = fopen($this->generateLocalFilePath($source), "r");
        while (!feof($handle)) {
            fgets($handle);
            $count++;
        }

        fclose($handle);

        return $count;
    }
}
