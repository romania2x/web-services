<?php

namespace App\Command\DataGovRo;

use App\Command\AbstractCommand;
use App\Entity\OpenData\Source;
use App\Message\DataGovRo\DataSetDetailsUpdate;
use App\Message\DataGovRo\DataSetDownload;
use App\Message\DataGovRo\DataSetProcess;
use App\Repository\Entity\OpenData\SourceRepository;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class RefreshCommand
 * @package App\Command\DataGovRo
 */
class RefreshCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'data-gov-ro:refresh';

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
     * @param MessageBusInterface    $messageBus
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MessageBusInterface $messageBus, EntityManagerInterface $entityManager)
    {
        $this->messageBus       = $messageBus;
        $this->sourceRepository = $entityManager->getRepository(Source::class);
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
        $sources = $this->sourceRepository->findBy($criteria);

        $progress = new ProgressBar($output);
        $progress->start();

        foreach ($sources as $source) {
            $this->messageBus->dispatch(new DataSetDetailsUpdate($source->getUrl(), $source->getType()));
            $progress->advance();
        }
        $progress->finish();
        $output->writeln("");
    }
}
