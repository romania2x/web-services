<?php

namespace App\Elasticsearch;

use App\Elasticsearch\Model\Company;
use App\Entity\OpenData\Source;
use Elasticsearch\Client as ElasticSearchClient;
use JMS\Serializer\Serializer;

/**
 * Class Indexer
 * @package App\Elasticsearch
 */
class Indexer
{
    private const INDEX_DATA_GOV_RO_COMPANIES = 'data-gov-ro_companies';
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
                    'index' => self::INDEX_DATA_GOV_RO_COMPANIES,
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
     * @param Source $source
     */
    public function indexCompany(Company $company, Source $source)
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
        $company->addSource($source->getUrl());
        $this->elasticSearchClient->index(
            [
                'index' => self::INDEX_DATA_GOV_RO_COMPANIES,
                'id' => $company->getNationalUniqueIdentification(),
                'body' => $this->serializer->toArray($company)
            ]
        );
    }
}
