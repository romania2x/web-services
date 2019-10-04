<?php

namespace App\Command\OSM;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadCountryCommand
 * @package App\Command\OpenStreetMap
 */
class LoadCountryCommand extends Command
{
    protected static $defaultName = 'osm:load-country';

    protected function configure()
    {
        $this
            ->addArgument('region', InputArgument::REQUIRED, 'Target region')
            ->addArgument('country', InputArgument::REQUIRED, 'Target country');

    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $streamContext = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )
        );

        $progress = new ProgressBar($output);

        $context = stream_context_create($streamContext, [
            'notification' => function (
                $notification_code,
                $severity,
                $message,
                $message_code,
                $bytes_transferred,
                $bytes_max
            ) use ($output, $progress) {
                switch ($notification_code) {
                    case STREAM_NOTIFY_FILE_SIZE_IS:
                        /** @var $progress ProgressBar */
                        $output->writeln("[<info>INFO</info>] Downloading geofabrik file");
                        $progress->start($bytes_max);
                        break;
                    case STREAM_NOTIFY_PROGRESS:
                        $progress->setProgress($bytes_transferred);
                        break;
                }
            }
        ]);

        $streamContent = file_get_contents(
            'http://download.geofabrik.de/' . $input->getArgument('region') . '/' . $input->getArgument('country') . '-latest.osm.pbf',
            false, $context
        );

        $progress->finish();

        $output->writeln("\n[<info>INFO</info>] Flushing file to disk ...");

        file_put_contents(
            $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $input->getArgument('region') . '-' . $input->getArgument('country') . '.osm.pbf',
            $streamContent
        );

        $output->writeln("[<info>INFO</info>] Load the data using this command: <question>osm2pgsql -H 127.0.0.1 -U postgres -W --create --database osm {$tmpPath}</question>");
    }
}
