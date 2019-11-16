<?php

namespace App\MessageHandler\Crawler\DataGovRo;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\DataSetProcess;
use App\Message\DataGovRo\PostalCodes\ProcessPostalCodes;
use App\Message\DataGovRo\Siruta\ProcessSiruta;
use App\MessageHandler\AbstractMessageHandler;
use App\Repository\Entity\OpenData\SourceRepository;

/**
 * Class DataSetProcessorHandler
 * @package App\MessageHandler\Crawler\DataGovRo
 */
class DataSetProcessorHandler extends AbstractMessageHandler
{
    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * DataSetProcessorHandler constructor.
     * @param SourceRepository $sourceRepository
     */
    public function __construct(SourceRepository $sourceRepository)
    {
        parent::__construct();
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * @param DataSetProcess $message
     */
    public function __invoke(DataSetProcess $message)
    {
        /** @var Source $source */
        $source = $this->sourceRepository->find($message->getSourceId());

        switch ($source->getType()) {
            case Source::TYPE_DATA_GOV_RO_SIRUTA:
                $this->messageBus->dispatch(new ProcessSiruta($source));
                break;
            case Source::TYPE_DATA_GOV_RO_POSTAL_CODES:
//                $this->messageBus->dispatch(new ProcessPostalCodes($source));
//                break;
            default:
                $this->log("No processor for {$source->getType()}");
        }
    }
}
