<?php

namespace App\Command\OSM;

use App\Model\OpenStreetMap\PlanetLine;
use App\Model\OpenStreetMap\PlanetPoint;
use App\Model\OpenStreetMap\PlanetPolygon;
use App\Model\OpenStreetMap\PlanetRoad;
use App\Message\IndexOpenStreetMapEntityMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class IndexCommand extends Command
{
    protected static $defaultName = 'osm:index';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * IndexCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface $messageBus
     */
    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityClasses = [PlanetRoad::class, PlanetPolygon::class, PlanetPoint::class, PlanetLine::class];

        foreach ($entityClasses as $entityClass) {

            $output->writeln("[<info>INFO</info>] Indexing entity " . $entityClass);
            $progress = new ProgressBar($output);

            $planetLineRepository = $this->entityManager->getRepository($entityClass);
            $total = $planetLineRepository->count();
            $progress->start($total);
            $roundLimit = 5000;
            $rounds = round($total / $roundLimit) + ($total % $roundLimit > 0 ? 1 : 0);

            for ($index = 0; $index < $rounds; $index++) {
                /** @var PlanetLine $planetLine */
                foreach ($planetLineRepository->findBy([], null, $roundLimit, $index * $roundLimit) as $planetLine) {
                    $progress->advance();
                    $this->messageBus->dispatch(new IndexOpenStreetMapEntityMessage($planetLine));
                }
            }

            $progress->finish();
        }
    }
}
