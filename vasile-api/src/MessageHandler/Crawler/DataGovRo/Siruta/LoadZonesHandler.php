<?php

namespace App\MessageHandler\Crawler\DataGovRo\Siruta;

use App\Message\DataGovRo\Siruta\LoadZones;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\Repository\Administrative\ZoneRepository;

/**
 * Class LoadZonesHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Siruta
 */
class LoadZonesHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var ZoneRepository
     */
    private $zoneRepository;


    /**
     * LoadZonesHandler constructor.
     * @param ZoneRepository $zoneRepository
     */
    public function __construct(ZoneRepository $zoneRepository)
    {
        parent::__construct();
        $this->zoneRepository = $zoneRepository;
    }

    /**
     * @param LoadZones $message
     */
    public function __invoke(LoadZones $message)
    {
        $this->log($message->getSource()->getTitle());

        $this->createProgressBar($this->getNoLinesFromSource($message->getSource()) + 1);

        $this->processCSVFromSource(
            $message->getSource(),
            function (array $row) {
                $this->zoneRepository->createFromSiruta($row);
                $this->progressBar->advance();
            },
            $message->getSeparator(),
            $message->getEncoding()
        );

        $this->finishProgressBar();
    }
}
