<?php
declare(strict_types = 1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 * @package App\Command
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * AbstractCommand constructor.
     */
    public function __construct()
    {
        parent::__construct(null);
        $this->output = new ConsoleOutput();
    }

    /**
     * @param string $message
     */
    public function log(string $message)
    {
        $this->output->writeln("[<info>DEBUG</info>] $message");
    }
}
