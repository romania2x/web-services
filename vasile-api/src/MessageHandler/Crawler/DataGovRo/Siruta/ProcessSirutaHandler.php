<?php

namespace App\MessageHandler\Crawler\DataGovRo\Siruta;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Siruta\LoadAdministrativeUnits;
use App\Message\DataGovRo\Siruta\LoadCounties;
use App\Message\DataGovRo\Siruta\LoadZones;
use App\Message\DataGovRo\Siruta\ProcessSiruta;
use App\MessageHandler\AbstractMessageHandler;
use App\MessageHandler\Crawler\DataGovRo\FileSystemAwareTrait;

/**
 * Class ProcessSirutaHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Siruta
 */
class ProcessSirutaHandler extends AbstractMessageHandler
{
    use FileSystemAwareTrait;

    private const ENCODING = 'ISO-8859-2';
    private const SEPARATOR = ';';

    /**
     * @param ProcessSiruta $message
     */
    public function __invoke(ProcessSiruta $message)
    {

        $zonesSirutaSource = $message->getSource()->getChildren()->filter(function (Source $child) {
            return $child->getTitle() == 'siruta-zone.csv';
        })->first();
        $this->messageBus->dispatch(
            new LoadZones($zonesSirutaSource, self::ENCODING, self::SEPARATOR)
        );

        $countiesSirutaSource = $message->getSource()->getChildren()->filter(function (Source $child) {
            return $child->getTitle() == 'siruta-judete.csv';
        })->first();

        $this->messageBus->dispatch(
            new LoadCounties($countiesSirutaSource, self::ENCODING, self::SEPARATOR)
        );

        $sirutaSource = $message->getSource()->getChildren()->filter(function (Source $child) {
            return $child->getTitle() == 'siruta.csv';
        })->first();

        $this->messageBus->dispatch(
            new LoadAdministrativeUnits($sirutaSource, self::ENCODING, self::SEPARATOR)
        );

        $this->graphEntityManager->clear();
    }
}
