<?php

namespace App\MessageHandler;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class AbstractMessageHandler
 * @package App\MessageHandler
 */
abstract class AbstractMessageHandler implements MessageHandlerInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * AbstractMessageHandler constructor.
     */
    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    /**
     * @param string $message
     * @param bool $rewind
     */
    protected function log(string $message, bool $rewind = false)
    {
        $className = str_replace('App\MessageHandler\Crawler\\', '', get_called_class());
        $message = "[<info>{$className}</info>] $message";

        if ($rewind) {
            $this->output->write("$message\r");
        } else {
            $this->output->writeln($message);
        }
    }
}
