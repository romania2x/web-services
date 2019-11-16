<?php

namespace App\MessageHandler\Crawler\DataGovRo\Siruta;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Siruta\LoadCounties;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\ModelCompositeBuilder\Administrative\CountyBuilder;
use App\Repository\Entity\Administrative\CountyRepository;
use App\Repository\Entity\Administrative\ZoneRepository;

/**
 * Class LoadCountiesHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Siruta
 */
class LoadCountiesHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    /**
     * @var CountyRepository
     */
    private $countyRepository;

    /**
     * @var ZoneRepository
     */
    private $zoneRepository;

    /**
     * LoadCountiesHandler constructor.
     * @param ZoneRepository   $zoneRepository
     * @param CountyRepository $countyRepository
     */
    public function __construct(ZoneRepository $zoneRepository, CountyRepository $countyRepository)
    {
        parent::__construct();
        $this->zoneRepository   = $zoneRepository;
        $this->countyRepository = $countyRepository;
    }

    /**
     * @param LoadCounties $message
     */
    public function __invoke(LoadCounties $message)
    {
        //load counties
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
        $this->countyRepository->createOrUpdate(
            CountyBuilder::fromSiruta($row),
            $this->zoneRepository->findOneBy(['nationalId' => intval($row['ZONA'])])
        );
        $this->progressBar->advance();
    }
}
