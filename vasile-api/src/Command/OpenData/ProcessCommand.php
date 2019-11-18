<?php

namespace App\Command\OpenData;

use App\Command\AbstractCommand;
use App\Entity\OpenData\Source;
use App\Message\DataGovRo\DataSetProcess;
use App\Repository\OpenData\SourceRepository;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class ProcessCommand
 * @package App\Command\DataGovRo
 */
class ProcessCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'od:process';

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * ProcessCommand constructor.
     * @param MessageBusInterface $messageBus
     * @param SourceRepository    $sourceRepository
     */
    public function __construct(MessageBusInterface $messageBus, SourceRepository $sourceRepository)
    {
        $this->messageBus       = $messageBus;
        $this->sourceRepository = $sourceRepository;
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addArgument('type', InputArgument::OPTIONAL, 'DataSet type');
    }


    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $criteria = ['downloadable' => false];
        if ($input->getArgument('type')) {
            $criteria['type'] = $input->getArgument('type');
        }

        /** @var Source[] $sources */
        $sources = $this->sourceRepository->getOGMRepository()->findBy($criteria);

        $progress = new ProgressBar($output);
        $progress->start();

        foreach ($sources as $source) {
            $this->messageBus->dispatch(new DataSetProcess($source));
            $progress->advance();
        }
        $progress->finish();
        $output->writeln("");
    }
}
