<?php

namespace App\Elasticsearch;

use App\Elasticsearch\Model\Company;
use Elasticsearch\Client as ElasticSearchClient;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;

/**
 * Class Indexer
 * @package App\Elasticsearch
 */
class Indexer
{
    /**
     * @var ElasticSearchClient
     */
    private $elasticSearchClient;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Indexer constructor.
     * @param ElasticSearchClient $elasticSearchClient
     * @param Serializer $serializer
     */
    public function __construct(ElasticSearchClient $elasticSearchClient, Serializer $serializer)
    {
        $this->elasticSearchClient = $elasticSearchClient;
        $this->serializer = $serializer;
    }

    /**
     * @param string $id
     * @return Company
     */
    public function retrieveCompanyById(string $id): ?Company
    {
        try {
            $entry = $this->elasticSearchClient->get(
                [
                    'index' => 'data-gov-ro_companies',
                    'id' => $id
                ]
            );
            return $this->serializer->fromArray($entry['_source'], Company::class);
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @param Company $company
     */
    public function indexCompany(Company $company)
    {
        $existingCompany = $this->retrieveCompanyById($company->getNationalUniqueIdentification());
        if ($existingCompany) {
            $existingCompany
                ->setLocality($company->getLocality())
                ->setCounty($company->getCounty())
                ->setFullAddress($company->getFullAddress())
                ->setEuropeanUniqueIdentification($company->getEuropeanUniqueIdentification());
            foreach ($company->getStateHistory() as $timestamp => $states) {
                $existingCompany->addStateHistory($timestamp, $states);
            }
            $company = $existingCompany;
        }
        $this->elasticSearchClient->index(
            [
                'index' => 'data-gov-ro_companies',
                'id' => $company->getNationalUniqueIdentification(),
                'body' => $this->serializer->toArray($company)
            ]
        );
    }
}
