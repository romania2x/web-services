<?php

declare(strict_types = 1);

namespace App\Repository\Administrative;

use App\Helpers\LanguageHelpers;
use App\Model\Administrative\Way;
use App\Repository\AbstractRepository;


class WayRepository extends AbstractRepository
{
    /**
     * @param array $row
     * @param int   $parentUnit
     * @return Way|null
     */
    public function createFromStreets(array $row, int $parentUnit): ?Way
    {
        $way = new Way();

        foreach ($row as $key => $value) {
            switch ($key) {
                case 'DENUMIRE':
                    $way->setName(LanguageHelpers::normalizeName($value));
                    $way->setSlug(LanguageHelpers::slugify($value));
                    break;
                case 'TAT_COD':
                    $way->setTitle(strtolower($value));
                    break;
                case 'COD':
                    $way->setCountyId(intval($value));
                    break;
                case 'COD_POSTAL':
                    if (is_string($value)) {
                        $way->addPostalCode($value);
                    }
                    break;
                case 'LOC_JUD_COD':
                case 'LOC_COD':
                case 'COD_POLITIE':
                case 'SAR_COD':
                case 'NR_SECTOR':
                case 'DATA_INF':
                case 'DATA_DESF':
                case 'COD_POLITIE_LOC':
                    break;
                default:
                    throw new \RuntimeException("Unknown field $key with value $value");
            }
        }

        trY {
            $result = $this->neo4jClient->run(
                <<<EOL
            match (pu:Administrative) where id(pu) = {parentUnitId}
            merge (w:Way:Administrative{countyId:{wayCountyId}})<-[:PARENT]-(pu)
            on create set w = {way}
            on match set w += {way}
            return id(w) as id
EOL
                ,
                [
                    'parentUnitId' => $parentUnit,
                    'wayCountyId'  => $way->getCountyId(),
                    'way'          => $this->serializer->toArray($way)
                ]
            );

            return $way->setId($result->firstRecord()->get('id'));
        } catch (\Exception $exception) {
            return null;
        }
    }
}
