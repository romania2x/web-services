<?php

namespace App\MessageHandler;

use GraphAware\Neo4j\OGM\EntityManager;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AbstractMessageHandler
 * @package App\MessageHandler
 */
abstract class AbstractMessageHandler implements MessageHandlerInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var ProgressBar
     */
    protected $progressBar;

    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * @var EntityManager
     */
    protected $graphEntityManager;

    /**
     * @var SerializerInterface
     */
    protected $serializer;


    /**
     * AbstractMessageHandler constructor.
     */
    public function __construct()
    {
        setlocale(LC_CTYPE, 'ro_RO');
        $this->output = new ConsoleOutput();
    }

    /**
     * @param MessageBusInterface $messageBus
     * @return AbstractMessageHandler
     */
    public function setMessageBus(MessageBusInterface $messageBus): AbstractMessageHandler
    {
        $this->messageBus = $messageBus;
        return $this;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return $this
     */
    public function setGraphEntityManager(EntityManagerInterface $entityManager): AbstractMessageHandler
    {
        $this->graphEntityManager = $entityManager;
        return $this;
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param string $message
     * @param bool   $rewind
     */
    protected function log(string $message, bool $rewind = false)
    {
        $className = str_replace('App\MessageHandler\Crawler\\', '', self::class);
        $message   = "[<info>{$className}</info>] $message";

        if ($rewind) {
            $this->output->write("$message\r");
        } else {
            $this->output->writeln($message);
        }
    }

    protected function createProgressBar(int $total)
    {
        $this->progressBar = new ProgressBar($this->output, $total);
        $className         = str_replace('App\MessageHandler\Crawler\\', '', self::class);
        $this->progressBar->setFormat("[<info>{$className}</info>] %current%/%max% %bar% %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%");
        $this->progressBar->setEmptyBarCharacter(' ');
        $this->progressBar->setBarCharacter('#');
        $this->progressBar->setProgressCharacter('#');
    }

    protected function finishProgressBar()
    {
        $this->progressBar->finish();
        $this->output->write(PHP_EOL);
    }

    protected function cleanMemory()
    {
        $this->graphEntityManager->clear();
        gc_collect_cycles();
    }
}
