<?php
declare(strict_types = 1);

namespace App\MessageHandler\Crawler\DataGovRo\Companies;

use App\Entity\OpenData\Source;
use App\Message\DataGovRo\Companies\LoadCompaniesSet;
use App\MessageHandler\AbstractMessageHandler;
use App\Repository\Entity\OpenData\SourceRepository;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use GraphAware\Neo4j\OGM\EntityManager;
use Symfony\Component\Panther\Client;

/**
 * Class LoadCompaniesSetHandler
 * @package App\MessageHandler\Crawler\DataGovRo\Companies
 */
class LoadCompaniesSetHandler extends AbstractMessageHandler
{
    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * LoadCompaniesSetHandler constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct();
        $this->sourceRepository = $entityManager->getRepository(Source::class);
    }

    /**
     * @param LoadCompaniesSet $message
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws \Exception
     */
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

        $companiesSetSource = $this->sourceRepository->createOrUpdate(
            $message->getSource(),
            $set['title'],
            $set['description']
        );

        /** @var RemoteWebElement $resource */
        foreach ($crawler->filter('.resource-list .resource-item') as $resource) {
            $resource = [
                'title' => trim($resource->findElement(WebDriverBy::cssSelector('.heading'))->getText()),
                'source' => $resource->findElement(WebDriverBy::cssSelector('ul.dropdown-menu > li:nth-child(2) > a'))->getAttribute('href')
            ];

            $set['resources'][] = $resource;

            $companiesSubSetSource = $this->sourceRepository->createOrUpdate(
                $resource['source'],
                $resource['title'],
                null,
                $companiesSetSource
            );
        }

    }
}
