<?php

namespace App\MessageHandler\Crawler\DataGovRo;

use App\Message\DataGovRo\SyncFirmsMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class SyncFirmsHandler
 * @package App\MessageHandler\Crawler\DataGovRo
 */
class SyncFirmsHandler implements MessageHandlerInterface
{
    /**
     * @param SyncFirmsMessage $message
     */
    public function __invoke(SyncFirmsMessage $message)
    {
        print_r($message);
    }
}
