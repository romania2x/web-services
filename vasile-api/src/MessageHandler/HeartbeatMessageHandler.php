<?php

namespace App\MessageHandler;

use App\Message\HeartbeatMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HeartbeatMessageHandler implements MessageHandlerInterface
{
    public function __invoke(HeartbeatMessage $message)
    {
    }
}
