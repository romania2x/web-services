<?php

namespace App\Command\DataGovRo;

use App\Command\AbstractCommand;
use App\Message\DataGovRo\DataSetDetailsUpdate;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class LoadCommand
 * @package App\Command\DataGovRo
 */
class LoadCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'data-gov-ro:load';

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
        $this->addArgument('source', InputArgument::REQUIRED, 'CSV with data.gov.ro\'s datasets links');
    }


    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log('Loading datasets');

        $sourceHandler = fopen($input->getArgument('source'), 'r');

        while ($row = fgetcsv($sourceHandler)) {
            $this->log("Processing {$row[1]} => '{$row[0]}'");
            $this->messageBus->dispatch(new DataSetDetailsUpdate($row[0], $row[1]));
        }

        $this->log('Done.');
    }
}
