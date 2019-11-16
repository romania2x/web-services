<?php

namespace App\MessageHandler\Crawler\DataGovRo\Siruta;

use App\Constants\Administrative\AdministrativeUnitType;
use App\Entity\Administrative\AdministrativeUnit;
use App\Entity\Administrative\County;
use App\Message\DataGovRo\Siruta\LoadAdministrativeUnits;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\ModelCompositeBuilder\Administrative\AdministrativeUnitBuilder;
use App\Repository\Entity\Administrative\AdministrativeUnitRepository;
use App\Repository\Entity\Administrative\CountyRepository;

/**
 * Class LoadAdministrativeUnitsHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Siruta
 */
class LoadAdministrativeUnitsHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var AdministrativeUnitRepository
     */
    private $administrativeUnitRepository;

    /**
     * @var CountyRepository
     */
    private $countyRepository;

    /**
     * LoadAdministrativeUnitsHandler constructor.
     * @param AdministrativeUnitRepository $administrativeUnitRepository
     * @param CountyRepository             $countyRepository
     */
    public function __construct(
        AdministrativeUnitRepository $administrativeUnitRepository,
        CountyRepository $countyRepository
    ) {
        parent::__construct();
        $this->administrativeUnitRepository = $administrativeUnitRepository;
        $this->countyRepository             = $countyRepository;
    }


    /**
     * @param LoadAdministrativeUnits $message
     */
    public function __invoke(LoadAdministrativeUnits $message)
    {
        $this->log($message->getSource()->getTitle());

        $this->createProgressBar($this->getNoLinesFromSource($message->getSource()) + 1);

        $this->processCSVFromSource(
            $message->getSource(),
            [$this, 'processRow'],
            $message->getSeparator(),
            $message->getEncoding()
        );
        $this->finishProgressBar();
    }

    /**
     * @param array $row
     * @throws \Exception
     */
    private function processRow(array $row)
    {
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
        $this->progressBar->advance();
    }
}
