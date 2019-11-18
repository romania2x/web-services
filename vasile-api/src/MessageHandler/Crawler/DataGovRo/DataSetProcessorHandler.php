<?php

namespace App\MessageHandler\Crawler\DataGovRo;

use App\Constants\DataSetType;
use App\Entity\OpenData\Source;
use App\Message\DataGovRo\DataSetProcess;
use App\Message\DataGovRo\Siruta\ProcessSiruta;
use App\Message\DataGovRo\Streets\ProcessStreets;
use App\MessageHandler\AbstractMessageHandler;
use App\Repository\OpenData\SourceRepository;

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
        $source = $this->sourceRepository->getOGMRepository()->find($message->getSourceId());

        switch ($source->getType()) {
            case DataSetType::DATA_GOV_RO_SIRUTA:
                $this->messageBus->dispatch(new ProcessSiruta($source));
                break;
            case DataSetType::DATA_GOV_RO_STREETS:
                $this->messageBus->dispatch(new ProcessStreets($source));
                break;
            case DataSetType::DATA_GOV_RO_POSTAL_CODES:
//                $this->messageBus->dispatch(new ProcessPostalCodes($source));
//                break;
            case DataSetType::DATA_GOV_RO_COMPANIES:
            default:
                $this->log("No processor for {$source->getType()}");
        }
    }
}
