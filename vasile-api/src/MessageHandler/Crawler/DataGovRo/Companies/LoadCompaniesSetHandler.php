<?php
declare(strict_types = 1);

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Message\DataGovRo\Companies\LoadCompaniesSet;
use App\MessageHandler\AbstractMessageHandler;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\Client;

/**
 * Class LoadCompaniesSetHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Companies
 */
class LoadCompaniesSetHandler extends AbstractMessageHandler
{
    public function __invoke(LoadCompaniesSet $message)
    {
        $this->log("Processing companies set {$message->getSource()}");

        $client = Client::createChromeClient();

        $client->request('GET', $message->getSource());
        $crawler = $client->waitFor('.module-content');

        $set = [
            'title' => trim($crawler->filter('.module-content h1')->text()),
            'description' => trim($crawler->filter('.module-content .notes')->text()),
            'resources' => []
        ];

        /** @var RemoteWebElement $resource */
        foreach ($crawler->filter('.resource-list .resource-item') as $resource) {
            $resource = [
                'name' => trim($resource->findElement(WebDriverBy::cssSelector('.heading'))->getText()),
                'resource' => $resource->findElement(WebDriverBy::cssSelector('ul.dropdown-menu > li:nth-child(2) > a'))->getAttribute('href')
            ];

            $set['resources'][] = $resource;
        }

        print_r($set);
        die;
    }
}
