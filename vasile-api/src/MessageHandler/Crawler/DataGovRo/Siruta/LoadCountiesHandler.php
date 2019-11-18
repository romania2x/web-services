<?php

namespace App\MessageHandler\Crawler\DataGovRo\Siruta;

use App\Message\DataGovRo\Siruta\LoadCounties;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;
use App\Repository\Administrative\CountyRepository;
use App\Repository\Administrative\ZoneRepository;

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
     * LoadCountiesHandler constructor.
     * @param CountyRepository $countyRepository
     */
    public function __construct(CountyRepository $countyRepository)
    {
        parent::__construct();
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
            function (array $row) {
                $this->countyRepository->createFromSiruta($row);
                $this->progressBar->advance();
            },
            $message->getSeparator(),
            $message->getEncoding()
        );
        $this->finishProgressBar();
    }
}
