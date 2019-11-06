<?php

namespace App\Command\Companies;

use App\Command\AbstractCommand;
use App\Message\DataGovRo\Companies\LoadCompaniesSet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class LoadCompaniesCommand
 * @package App\Command\Companies
 */
class LoadCompaniesCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'companies:load';

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * LoadCompaniesCommand constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addArgument('source', InputArgument::REQUIRED, 'CSV with companies sets links');
    }


    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log('Loading companies');

        $sourceHandler = fopen($input->getArgument('source'), 'r');

        while ($row = fgetcsv($sourceHandler)) {
            $this->log("Processing '{$row[0]}'");
            $this->messageBus->dispatch(new LoadCompaniesSet($row[1]));
        }

        $this->log('Done.');
    }
}
