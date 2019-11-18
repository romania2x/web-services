<?php

declare(strict_types = 1);

namespace App\Repository\Administrative;

use App\Helpers\LanguageHelpers;
use App\Model\Administrative\County;
use App\Repository\AbstractRepository;

/**
 * Class CountyRepository
 * @package App\Repository\Administrative
 */
class CountyRepository extends AbstractRepository
{
    /**
     * @param array $row
     * @return County
     */
    public function createFromSiruta(array $row): County
    {
        $county = new County();

        foreach ($row as $key => $value) {
            switch ($key) {
                case 'JUD':
                    $county->setNationalId(intval($value));
                    break;
                case 'DENJ':
                    $county->setName(LanguageHelpers::normalizeName($value));
                    $county->setSlug(LanguageHelpers::slugify($value));
                    break;
                case 'MNEMONIC':
                    $county->setMnemonic($value);
                    break;
                case 'FSJ':
                    $county->setSortingIndex(intval($value));
                    break;
                case 'ZONA':
                    //noop
                    break;
                default:
                    throw new \RuntimeException("Unknown field $key with value $value");
            }
        }

        $result = $this->neo4jClient->run(
            <<<EOL
                match (z:Zone:Administrative{nationalId:{zoneNationalId}})
                merge (c:County:Administrative{nationalId:{nationalId}})
                on create set c = {county}
                on match set c += {county}
                merge (z)-[:PARENT]->(c)
                return id(c) as id
EOL
            ,
            [
                'zoneNationalId' => intval($row['ZONA']),
                'nationalId'     => $county->getNationalId(),
                'county'         => $this->serializer->toArray($county)
            ]
        );

        $county->setId($result->firstRecord()->get('id'));

        return $county;
    }
}
