<?php

namespace App\MessageHandler\Crawler\DataGovRo\Siruta;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Siruta\LoadZones;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\ModelCompositeBuilder\Administrative\ZoneBuilder;
use App\Repository\Entity\Administrative\ZoneRepository;
use Symfony\Component\Console\Helper\ProgressBar;

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
        $this->zoneRepository->createOrUpdate(ZoneBuilder::fromSiruta($row));
        $this->progressBar->advance();
    }
}
