<?php
declare(strict_types = 1);

namespace App\Repository\Administrative;

use App\Helpers\LanguageHelpers;
use App\Model\Administrative\Zone;
use App\Repository\AbstractRepository;

/**
 * Class ZoneRepository
 * @package App\Repository\Administrative
 */
class ZoneRepository extends AbstractRepository
{
    /**
     * @param array $row
     * @return Zone
     * @throws \Exception
     */
    public function createFromSiruta(array $row): Zone
    {
        $zone = new Zone();
        foreach ($row as $key => $value) {
            switch ($key) {
                case 'ZONA':
                    $zone->setNationalId(intval($value));
                    break;
                case 'SIRUTA':
                    $zone->setSiruta(intval($value));
                    break;
                case 'DENZONA':
                    $zone->setName(LanguageHelpers::normalizeName($value));
                    $zone->setSlug(LanguageHelpers::slugify($value));
                    break;
                default:
                    throw new \RuntimeException("Unknown field $key with value $value");
            }
        }

        $result = $this->neo4jClient->run(
            <<<EOL
            merge (z:Zone:Administrative{nationalId:{nationalId}})
            on create set z = {zone}
            on match set z += {zone}
            return id(z) as id
EOL
            ,
            [
                'nationalId' => $zone->getNationalId(),
                'zone'       => $this->serializer->toArray($zone)
            ]
        );

        $zone->setId($result->firstRecord()->get('id'));

        return $zone;
    }
}
