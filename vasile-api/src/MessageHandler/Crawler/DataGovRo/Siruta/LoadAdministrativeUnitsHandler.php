<?php

namespace App\MessageHandler\Crawler\DataGovRo\Siruta;

use App\Message\DataGovRo\Siruta\LoadAdministrativeUnits;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\Repository\Administrative\UnitRepository;
use App\Repository\Administrative\CountyRepository;

/**
 * Class LoadAdministrativeUnitsHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Siruta
 */
class LoadAdministrativeUnitsHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var UnitRepository
     */
    private $administrativeUnitRepository;

    /**
     * LoadAdministrativeUnitsHandler constructor.
     * @param UnitRepository $administrativeUnitRepository
     */
    public function __construct(UnitRepository $administrativeUnitRepository)
    {
        parent::__construct();
        $this->administrativeUnitRepository = $administrativeUnitRepository;
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
            function (array $row) {
                $this->administrativeUnitRepository->createFromSiruta($row);
                $this->progressBar->advance();
            },
            $message->getSeparator(),
            $message->getEncoding()
        );
        $this->graphEntityManager->clear();
        $this->finishProgressBar();
    }
}
